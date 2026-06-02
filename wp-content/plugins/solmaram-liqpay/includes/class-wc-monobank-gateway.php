<?php
defined( 'ABSPATH' ) || exit;

class WC_SM_Monobank_Gateway extends WC_Payment_Gateway {

    private const API_URL = 'https://api.monobank.ua/api/merchant/invoice/create';

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

    public function handle_callback() {
        $body = file_get_contents( 'php://input' );
        if ( ! $body ) { status_header( 400 ); exit; }

        // Verify X-Sign header: base64( sha256( base64(body) + token ) )
        $token    = $this->get_option( 'token' );
        $x_sign   = $_SERVER['HTTP_X_SIGN'] ?? '';
        $expected = base64_encode( hash( 'sha256', base64_encode( $body ) . $token, true ) );
        if ( ! hash_equals( $expected, $x_sign ) ) {
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
            $order->payment_complete( $invoice_id );
            $order->add_order_note( __( 'Monobank payment confirmed.', 'solmaram' ) );
        } elseif ( in_array( $status, [ 'failure', 'reversed' ], true ) ) {
            $order->update_status( 'failed', __( 'Monobank payment failed.', 'solmaram' ) );
        }

        status_header( 200 );
        exit;
    }
}
