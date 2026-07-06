<?php
defined( 'ABSPATH' ) || exit;

class WC_SM_Monobank_Gateway extends WC_Payment_Gateway {

    private const API_URL         = 'https://api.monobank.ua/api/merchant/invoice/create';
    private const PUBKEY_URL       = 'https://api.monobank.ua/api/merchant/pubkey';
    private const PUBKEY_TRANSIENT = 'sm_monobank_pubkey';

    public function __construct() {
        $this->id                 = 'sm_monobank';
        $this->has_fields         = false;
        $this->method_title       = __( 'Monobank Pay', 'solmaram' );
        $this->method_description = __( 'Pay via Monobank Acquiring API.', 'solmaram' );

        $this->init_form_fields();
        $this->init_settings();

        $this->title   = $this->get_option( 'title' );
        $this->enabled = $this->get_option( 'enabled' );

        add_action( 'woocommerce_api_sm_monobank_callback', [ $this, 'handle_callback' ] );
        add_action( 'woocommerce_update_options_payment_gateways_' . $this->id, [ $this, 'process_admin_options' ] );
    }

    public function init_form_fields() {
        $this->form_fields = [
            'enabled' => [ 'title' => __( 'Enable', 'solmaram' ), 'type' => 'checkbox', 'default' => 'yes' ],
            'title'   => [ 'title' => __( 'Title', 'solmaram' ), 'type' => 'text', 'default' => __( 'Monobank Pay', 'solmaram' ) ],
            'token'   => [ 'title' => __( 'Monobank Token', 'solmaram' ), 'type' => 'password', 'default' => '' ],
        ];
    }

    public function process_payment( $order_id ): array {
        $order = wc_get_order( $order_id );
        if ( ! $order ) {
            wc_add_notice( __( 'Order not found.', 'solmaram' ), 'error' );
            return [ 'result' => 'failure' ];
        }

        $token = $this->get_option( 'token' );
        if ( ! $token ) {
            wc_add_notice( __( 'Payment gateway is not configured.', 'solmaram' ), 'error' );
            return [ 'result' => 'failure' ];
        }

        $response = wp_remote_post( self::API_URL, [
            'headers' => [
                'X-Token'      => $token,
                'Content-Type' => 'application/json',
            ],
            'body'    => wp_json_encode( [
                'amount'      => (int) round( (float) $order->get_total() * 100 ),
                'ccy'         => 980, // UAH ISO 4217
                'merchantPaymInfo' => [
                    'reference'   => (string) $order->get_id(),
                    'destination' => sprintf( __( 'Order #%d — SolMaram', 'solmaram' ), $order->get_id() ),
                    'basketOrder' => array_map( fn( $item ) => [
                        'name'  => $item->get_name(),
                        'qty'   => $item->get_quantity(),
                        'sum'   => (int) round( (float) $item->get_subtotal() * 100 ),
                        'icon'  => '',
                        'unit'  => __( 'pcs', 'solmaram' ),
                        'code'  => (string) $item->get_product_id(),
                    ], array_values( $order->get_items() ) ),
                ],
                'redirectUrl' => esc_url( $this->get_return_url( $order ) ),
                'webHookUrl'  => esc_url( home_url( '/wc-api/sm_monobank_callback/' ) ),
            ] ),
        ] );

        if ( is_wp_error( $response ) || wp_remote_retrieve_response_code( $response ) !== 200 ) {
            wc_add_notice( __( 'Payment failed. Please try again.', 'solmaram' ), 'error' );
            return [ 'result' => 'failure' ];
        }

        $body    = json_decode( wp_remote_retrieve_body( $response ), true );
        $pay_url = $body['pageUrl'] ?? '';

        if ( ! $pay_url ) {
            wc_add_notice( __( 'Could not create Monobank invoice.', 'solmaram' ), 'error' );
            return [ 'result' => 'failure' ];
        }

        $order->update_status( 'pending', __( 'Awaiting Monobank payment.', 'solmaram' ) );
        WC()->cart->empty_cart();

        return [ 'result' => 'success', 'redirect' => $pay_url ];
    }

    /**
     * Fetch Monobank's ECDSA public key (base64-encoded PEM) from the merchant
     * pubkey endpoint. Cached for a day; pass $force_refresh to bypass the cache
     * (used to handle key rotation when a verification fails).
     */
    private function get_public_key( bool $force_refresh = false ): string {
        if ( ! $force_refresh ) {
            $cached = get_transient( self::PUBKEY_TRANSIENT );
            if ( is_string( $cached ) && $cached !== '' ) {
                return $cached;
            }
        }

        $token = $this->get_option( 'token' );
        if ( ! $token ) return '';

        $response = wp_remote_get( self::PUBKEY_URL, [
            'timeout' => 10,
            'headers' => [ 'X-Token' => $token ],
        ] );
        if ( is_wp_error( $response ) || wp_remote_retrieve_response_code( $response ) !== 200 ) {
            return '';
        }

        $body = json_decode( wp_remote_retrieve_body( $response ), true );
        $key  = (string) ( $body['key'] ?? '' );
        if ( $key !== '' ) {
            set_transient( self::PUBKEY_TRANSIENT, $key, DAY_IN_SECONDS );
        }
        return $key;
    }

    /**
     * Verify Monobank's X-Sign webhook header: a base64 ECDSA-SHA256 signature
     * over the raw request body, checked against the merchant public key. Tries
     * the cached key first, then re-fetches once (key rotation) before failing.
     */
    private function verify_signature( string $body, string $x_sign ): bool {
        if ( $x_sign === '' ) return false;
        $sig = base64_decode( $x_sign, true );
        if ( $sig === false || $sig === '' ) return false;

        foreach ( [ false, true ] as $force_refresh ) {
            $key_b64 = $this->get_public_key( $force_refresh );
            if ( $key_b64 === '' ) continue;
            $pem = base64_decode( $key_b64, true );
            if ( $pem === false ) continue;
            if ( self::verify_body_signature( $body, $sig, $pem ) ) {
                return true;
            }
            // Bad signature or verify error: on the first pass, retry with a
            // freshly fetched key in case Monobank rotated it; then give up.
        }
        return false;
    }

    /**
     * Pure ECDSA-SHA256 verification of a raw body against a PEM public key.
     * Static and dependency-free so the crypto can be unit-tested in isolation.
     */
    public static function verify_body_signature( string $body, string $signature_raw, string $pubkey_pem ): bool {
        $pubkey = openssl_pkey_get_public( $pubkey_pem );
        if ( ! $pubkey ) return false;
        return openssl_verify( $body, $signature_raw, $pubkey, OPENSSL_ALGO_SHA256 ) === 1;
    }

    public function handle_callback() {
        $body = file_get_contents( 'php://input' );
        if ( ! $body ) { status_header( 400 ); exit; }

        // Verify the X-Sign header (base64 ECDSA-SHA256 over the raw body) against
        // Monobank's merchant public key. See Monobank Acquiring API docs.
        $x_sign = $_SERVER['HTTP_X_SIGN'] ?? '';
        if ( ! $this->verify_signature( $body, $x_sign ) ) {
            status_header( 400 );
            exit( 'Invalid signature' );
        }

        $payload = json_decode( $body, true );
        if ( ! $payload ) { status_header( 400 ); exit; }

        $invoice_id = sanitize_text_field( $payload['invoiceId'] ?? '' );
        $status     = sanitize_text_field( $payload['status'] ?? '' );
        $reference  = absint( $payload['reference'] ?? 0 );
        $order      = wc_get_order( $reference );
        if ( ! $order ) exit;

        if ( $status === 'success' && ! $order->is_paid() ) {
            // Defense-in-depth: confirm amount (minor units) + currency (980 UAH)
            // match the order before completing payment.
            $paid_amount = (int) ( $payload['amount'] ?? 0 );
            $paid_ccy    = (int) ( $payload['ccy'] ?? 0 );
            $expected    = (int) round( (float) $order->get_total() * 100 );
            if ( abs( $paid_amount - $expected ) > 1 || ( $paid_ccy && $paid_ccy !== 980 ) ) {
                $order->add_order_note( sprintf(
                    /* translators: 1: paid amount (minor units), 2: paid currency code, 3: expected amount */
                    __( 'Monobank callback rejected — amount/currency mismatch: got %1$d (ccy %2$d), expected %3$d (980).', 'solmaram' ),
                    $paid_amount, $paid_ccy, $expected
                ) );
                status_header( 400 );
                exit( 'Amount mismatch' );
            }
            $order->payment_complete( $invoice_id );
            $order->add_order_note( __( 'Monobank payment confirmed.', 'solmaram' ) );
        } elseif ( in_array( $status, [ 'failure', 'reversed' ], true ) ) {
            $order->update_status( 'failed', __( 'Monobank payment failed.', 'solmaram' ) );
        }

        status_header( 200 );
        exit;
    }
}
