<?php
/**
 * Plugin Name: SolMaram LiqPay & Monobank Gateways
 * Description: WooCommerce payment gateways — LiqPay (card, Google Pay, Apple Pay) and Monobank Pay.
 * Version:     1.0.0
 * Author:      SolMaram
 * Text Domain: solmaram
 * Requires at least: 6.4
 * Requires PHP: 8.1
 * WC requires at least: 8.0
 */
defined( 'ABSPATH' ) || exit;

define( 'SM_LIQPAY_PATH', plugin_dir_path( __FILE__ ) );
define( 'SM_LIQPAY_URL',  plugin_dir_url( __FILE__ ) );

add_action( 'plugins_loaded', function () {
    if ( ! class_exists( 'WC_Payment_Gateway' ) ) return;

    require_once SM_LIQPAY_PATH . 'includes/class-liqpay-api.php';
    require_once SM_LIQPAY_PATH . 'includes/class-wc-liqpay-gateway.php';
    require_once SM_LIQPAY_PATH . 'includes/class-wc-monobank-gateway.php';

    add_filter( 'woocommerce_payment_gateways', function ( $gateways ) {
        $gateways[] = 'WC_SM_LiqPay_Gateway';
        $gateways[] = 'WC_SM_Monobank_Gateway';
        return $gateways;
    } );
} );
