<?php
/**
 * Plugin Name: SolMaram Core
 * Description: Core functionality for SolMaram — recipe meta, free-shipping bar, Instagram feed, CSV export, trust counters, AJAX filters.
 * Version:     1.0.0
 * Author:      SolMaram
 * Text Domain: solmaram
 * Domain Path: /languages
 * Requires at least: 6.4
 * Requires PHP: 8.1
 * WC requires at least: 8.0
 */
defined( 'ABSPATH' ) || exit;

define( 'SM_CORE_PATH', plugin_dir_path( __FILE__ ) );
define( 'SM_CORE_URL',  plugin_dir_url( __FILE__ ) );

add_action( 'plugins_loaded', function () {
    if ( ! class_exists( 'WooCommerce' ) ) return;

    require_once SM_CORE_PATH . 'includes/class-recipe-meta.php';
    require_once SM_CORE_PATH . 'includes/class-free-shipping-bar.php';
    require_once SM_CORE_PATH . 'includes/class-instagram-feed.php';
    require_once SM_CORE_PATH . 'includes/class-csv-export.php';
    require_once SM_CORE_PATH . 'includes/class-trust-counters.php';
    require_once SM_CORE_PATH . 'admin/settings-page.php';

    SM_Recipe_Meta::init();
    SM_Free_Shipping_Bar::init();
    SM_Instagram_Feed::init();
    SM_CSV_Export::init();
    SM_Trust_Counters::init();
    SM_Admin_Settings::init();

    /* ── AJAX: product filter (FR-01.3) ──────────────────────────── */
    add_action( 'wp_ajax_solmaram_filter_products',        'solmaram_ajax_filter_products' );
    add_action( 'wp_ajax_nopriv_solmaram_filter_products', 'solmaram_ajax_filter_products' );
} );

function solmaram_ajax_filter_products() {
    check_ajax_referer( 'sm_filter', 'nonce' );

    $paged     = max( 1, absint( $_POST['paged'] ?? 1 ) );
    $orderby   = sanitize_key( $_POST['orderby'] ?? 'popularity' );
    $min_price = floatval( $_POST['min_price'] ?? 0 );
    $max_price = floatval( $_POST['max_price'] ?? 0 );

    $cats      = array_map( 'sanitize_key', (array) ( $_POST['product_cat'] ?? [] ) );
    $use_cases = array_map( 'sanitize_key', (array) ( $_POST['use_case'] ?? [] ) );

    $tax_query = [ 'relation' => 'AND' ];
    if ( $cats ) {
        $tax_query[] = [
            'taxonomy' => 'product_cat',
            'field'    => 'slug',
            'terms'    => $cats,
        ];
    }
    if ( $use_cases ) {
        $tax_query[] = [
            'taxonomy' => 'product_tag',
            'field'    => 'slug',
            'terms'    => $use_cases,
        ];
    }

    $order_map = [
        'popularity' => [ 'orderby' => 'meta_value_num', 'meta_key' => 'total_sales', 'order' => 'DESC' ],
        'date'       => [ 'orderby' => 'date', 'order' => 'DESC' ],
        'price'      => [ 'orderby' => 'meta_value_num', 'meta_key' => '_price', 'order' => 'ASC' ],
        'price-desc' => [ 'orderby' => 'meta_value_num', 'meta_key' => '_price', 'order' => 'DESC' ],
    ];
    $order_args = $order_map[ $orderby ] ?? $order_map['popularity'];

    $args = array_merge( [
        'post_type'      => 'product',
        'post_status'    => 'publish',
        'posts_per_page' => 12,
        'paged'          => $paged,
        'tax_query'      => $tax_query,
    ], $order_args );

    if ( $min_price || $max_price ) {
        $args['meta_query'] = [ [
            'key'     => '_price',
            'value'   => array_filter( [ $min_price ?: null, $max_price ?: null ] ),
            'compare' => $min_price && $max_price ? 'BETWEEN' : ( $min_price ? '>=' : '<=' ),
            'type'    => 'NUMERIC',
        ] ];
    }

    $query = new WP_Query( $args );

    ob_start();
    if ( $query->have_posts() ) {
        while ( $query->have_posts() ) {
            $query->the_post();
            wc_get_template_part( 'content', 'product' );
        }
        wp_reset_postdata();
    } else {
        echo '<p class="no-products">' . esc_html__( 'No products found.', 'solmaram' ) . '</p>';
    }
    $html = ob_get_clean();

    ob_start();
    echo paginate_links( [
        'total'   => $query->max_num_pages,
        'current' => $paged,
        'prev_text' => '&lsaquo;',
        'next_text' => '&rsaquo;',
    ] );
    $pagination = ob_get_clean();

    wp_send_json_success( [ 'html' => $html, 'pagination' => $pagination ] );
}
