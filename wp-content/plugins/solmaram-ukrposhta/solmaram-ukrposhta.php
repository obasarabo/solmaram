<?php
/**
 * Plugin Name: SolMaram Ukrposhta Shipping
 * Description: Flat-rate Ukrposhta shipping method for WooCommerce.
 * Version:     1.0.0
 * Author:      SolMaram
 * Text Domain: solmaram
 * Requires at least: 6.4
 * Requires PHP: 8.1
 * WC requires at least: 8.0
 */
defined( 'ABSPATH' ) || exit;

add_action( 'plugins_loaded', function () {
    if ( ! class_exists( 'WC_Shipping_Method' ) ) return;

    require_once plugin_dir_path( __FILE__ ) . 'includes/class-wc-ukrposhta-shipping.php';

    add_filter( 'woocommerce_shipping_methods', function ( $methods ) {
        $methods['sm_ukrposhta'] = 'WC_SM_Ukrposhta_Shipping';
        return $methods;
    } );
} );
