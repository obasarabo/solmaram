<?php
/**
 * Plugin Name: SolMaram Nova Poshta Shipping
 * Description: WooCommerce shipping method with real-time Nova Poshta API integration — branch pickup and courier delivery.
 * Version:     1.0.0
 * Author:      SolMaram
 * Text Domain: solmaram
 * Requires at least: 6.4
 * Requires PHP: 8.1
 * WC requires at least: 8.0
 */
defined( 'ABSPATH' ) || exit;

define( 'SM_NP_PATH', plugin_dir_path( __FILE__ ) );
define( 'SM_NP_URL',  plugin_dir_url( __FILE__ ) );

add_action( 'plugins_loaded', function () {
    if ( ! class_exists( 'WC_Shipping_Method' ) ) return;

    require_once SM_NP_PATH . 'includes/class-nova-poshta-api.php';
    require_once SM_NP_PATH . 'includes/class-wc-nova-poshta-shipping.php';
    require_once SM_NP_PATH . 'includes/class-np-address-saver.php';

    add_filter( 'woocommerce_shipping_methods', function ( $methods ) {
        $methods['sm_nova_poshta'] = 'WC_SM_Nova_Poshta_Shipping';
        return $methods;
    } );

    SM_NP_Address_Saver::init();

    // AJAX endpoints for city / warehouse lookup
    add_action( 'wp_ajax_np_get_cities',        'sm_np_ajax_get_cities' );
    add_action( 'wp_ajax_nopriv_np_get_cities', 'sm_np_ajax_get_cities' );

    add_action( 'wp_ajax_np_get_warehouses',        'sm_np_ajax_get_warehouses' );
    add_action( 'wp_ajax_nopriv_np_get_warehouses', 'sm_np_ajax_get_warehouses' );

    add_action( 'wp_ajax_np_set_city_ref',        'sm_np_ajax_set_city_ref' );
    add_action( 'wp_ajax_nopriv_np_set_city_ref', 'sm_np_ajax_set_city_ref' );
} );

function sm_np_ajax_get_cities() {
    check_ajax_referer( 'sm_np', 'nonce' );
    $query = sanitize_text_field( $_POST['query'] ?? '' );
    if ( strlen( $query ) < 2 ) wp_send_json_error();

    $api   = new SM_Nova_Poshta_API( get_option( 'sm_np_api_key', '' ) );
    $cities = $api->get_cities( $query );
    wp_send_json_success( $cities );
}

function sm_np_ajax_set_city_ref() {
    check_ajax_referer( 'sm_np', 'nonce' );
    $city_ref = sanitize_text_field( wp_unslash( $_POST['city_ref'] ?? '' ) );
    if ( $city_ref ) {
        WC()->session->set( 'np_city_ref', $city_ref );
    }
    wp_send_json_success();
}

function sm_np_ajax_get_warehouses() {

    check_ajax_referer( 'sm_np', 'nonce' );
    $city_ref = sanitize_text_field( $_POST['city_ref'] ?? '' );
    if ( ! $city_ref ) wp_send_json_error();

    $api       = new SM_Nova_Poshta_API( get_option( 'sm_np_api_key', '' ) );
    $warehouses = $api->get_warehouses( $city_ref );
    wp_send_json_success( $warehouses );
}
