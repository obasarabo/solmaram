<?php
defined( 'ABSPATH' ) || exit;

class WC_SM_LiqPay_Gateway extends WC_Payment_Gateway {

    public function __construct() {
        $this->id                 = 'sm_liqpay';
        $this->icon               = SM_LIQPAY_URL . 'assets/img/liqpay.svg';
        $this->has_fields         = false;
        $this->method_title       = __( 'LiqPay (Card / Google Pay / Apple Pay)', 'solmaram' );
        $this->method_description = __( 'Pay via LiqPay — supports Visa, Mastercard, Google Pay, Apple Pay.', 'solmaram' );

        $this->init_form_fields();
        $this->init_settings();

        $this->title       = $this->get_option( 'title' );
        $this->description = $this->get_option( 'description' );
        $this->enabled     = $this->get_option( 'enabled' );

        add_action( 'woocommerce_api_sm_liqpay_callback', [ $this, 'handle_callback' ] );
        add_action( 'woocommerce_update_options_payment_gateways_' . $this->id, [ $this, 'process_admin_options' ] );
    }

    public function init_form_fields() {
        $this->form_fields = [
            'enabled'     => [ 'title' => __( 'Enable', 'solmaram' ), 'type' => 'checkbox', 'default' => 'yes' ],
            'title'       => [ 'title' => __( 'Title', 'solmaram' ), 'type' => 'text', 'default' => __( 'Card / Google Pay / Apple Pay', 'solmaram' ) ],
            'description' => [ 'title' => __( 'Description', 'solmaram' ), 'type' => 'textarea', 'default' => __( 'Secure payment via LiqPay.', 'solmaram' ) ],
            'public_key'  => [ 'title' => __( 'LiqPay Public Key', 'solmaram' ), 'type' => 'text', 'default' => '' ],
            'private_key' => [ 'title' => __( 'LiqPay Private Key', 'solmaram' ), 'type' => 'password', 'default' => '' ],
            'sandbox'     => [ 'title' => __( 'Sandbox Mode', 'solmaram' ), 'type' => 'checkbox', 'default' => 'yes', 'description' => __( 'Use LiqPay sandbox for testing.', 'solmaram' ) ],
        ];
    }

    private function get_api(): SM_LiqPay_API {
        return new SM_LiqPay_API(
            $this->get_option( 'public_key' ),
            $this->get_option( 'private_key' )
        );
    }

    public function process_payment( $order_id ): array {
        $order = wc_get_order( $order_id );
        if ( ! $order ) {
            wc_add_notice( __( 'Order not found.', 'solmaram' ), 'error' );
            return [ 'result' => 'failure' ];
        }

        $api    = $this->get_api();
        $is_sb  = $this->get_option( 'sandbox' ) === 'yes' ? 1 : 0;

        $params = [
            'action'      => 'pay',
            'amount'      => number_format( (float) $order->get_total(), 2, '.', '' ),
            'currency'    => 'UAH',
            'description' => sprintf( __( 'Order #%d — SolMaram', 'solmaram' ), $order->get_id() ),
            'order_id'    => (string) $order->get_id(),
            'version'     => '3',
            'sandbox'     => $is_sb,
            'server_url'  => home_url( '/wc-api/sm_liqpay_callback/' ),
            'result_url'  => $this->get_return_url( $order ),
        ];

        $checkout_url = $api->get_checkout_url( $params );

        // Mark order as awaiting payment
        $order->update_status( 'pending', __( 'Awaiting LiqPay payment.', 'solmaram' ) );
        WC()->cart->empty_cart();

        return [
            'result'   => 'success',
            'redirect' => $checkout_url,
        ];
    }

    public function handle_callback() {
        $data      = wp_unslash( $_POST['data'] ?? '' );
        $signature = wp_unslash( $_POST['signature'] ?? '' );

        if ( ! $data || ! $signature ) {
            status_header( 400 );
            exit( 'Bad request' );
        }

        $api = $this->get_api();
        if ( ! $api->verify_signature( $data, $signature ) ) {
            status_header( 400 );
            exit( 'Invalid signature' );
        }

        $response = $api->decode_callback( $data );
        if ( ! $response ) {
            status_header( 400 );
            exit( 'Invalid data' );
        }

        $order_id = absint( $response['order_id'] ?? 0 );
        $order    = wc_get_order( $order_id );
        if ( ! $order ) exit;

        $status = $response['status'] ?? '';

        if ( in_array( $status, [ 'success', 'sandbox' ], true ) && ! $order->is_paid() ) {
            $order->payment_complete( $response['payment_id'] ?? '' );
            $order->add_order_note( __( 'LiqPay payment confirmed.', 'solmaram' ) );
        } elseif ( $status === 'failure' ) {
            $order->update_status( 'failed', __( 'LiqPay payment failed.', 'solmaram' ) );
        }

        exit;
    }
}
