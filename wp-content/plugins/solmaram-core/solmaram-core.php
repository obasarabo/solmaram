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

/* ── Use-case taxonomy (FR-01.2) ──────────────────────────────────────── */
add_action( 'init', function () {
    register_taxonomy( 'sm_use_case', 'product', [
        'labels'            => [
            'name'          => __( 'Use Cases', 'solmaram' ),
            'singular_name' => __( 'Use Case', 'solmaram' ),
            'menu_name'     => __( 'Use Cases', 'solmaram' ),
        ],
        'hierarchical'      => false,
        'public'            => true,
        'show_ui'           => true,
        'show_in_rest'      => true,
        'show_admin_column' => true,
        'rewrite'           => [ 'slug' => 'use-case', 'with_front' => false ],
    ] );

    // Seed default terms on first run
    $terms = [
        'snack'      => __( 'Снек', 'solmaram' ),
        'cooking'    => __( 'Приготування', 'solmaram' ),
        'ready-meal' => __( 'Готова страва', 'solmaram' ),
    ];
    foreach ( $terms as $slug => $name ) {
        if ( ! term_exists( $slug, 'sm_use_case' ) ) {
            wp_insert_term( $name, 'sm_use_case', [ 'slug' => $slug ] );
        }
    }
} );

/* ── One-time migration: product_tag use cases → sm_use_case ─────────── */
add_action( 'init', function () {
    if ( get_option( 'sm_use_case_migrated' ) ) return;

    $use_case_slugs = [ 'snack', 'cooking', 'ready-meal' ];
    $products = get_posts( [
        'post_type'      => 'product',
        'post_status'    => 'publish',
        'posts_per_page' => -1,
        'fields'         => 'ids',
    ] );

    foreach ( $products as $product_id ) {
        $tags = wp_get_object_terms( $product_id, 'product_tag', [ 'fields' => 'slugs' ] );
        $use_cases = array_intersect( $tags, $use_case_slugs );
        if ( $use_cases ) {
            wp_set_object_terms( $product_id, array_values( $use_cases ), 'sm_use_case', false );
        }
    }

    // Remove migrated slugs from product_tag
    foreach ( $use_case_slugs as $slug ) {
        $term = get_term_by( 'slug', $slug, 'product_tag' );
        if ( $term ) wp_delete_term( $term->term_id, 'product_tag' );
    }

    update_option( 'sm_use_case_migrated', true );
}, 20 );

add_action( 'plugins_loaded', function () {
    if ( ! class_exists( 'WooCommerce' ) ) return;

    require_once SM_CORE_PATH . 'includes/class-recipe-meta.php';
    require_once SM_CORE_PATH . 'includes/class-free-shipping-bar.php';
    require_once SM_CORE_PATH . 'includes/class-instagram-feed.php';
    require_once SM_CORE_PATH . 'includes/class-csv-export.php';
    require_once SM_CORE_PATH . 'includes/class-trust-counters.php';
    require_once SM_CORE_PATH . 'includes/class-inventory-manager.php';
    require_once SM_CORE_PATH . 'includes/class-product-i18n.php';
    require_once SM_CORE_PATH . 'admin/settings-page.php';

    SM_Recipe_Meta::init();
    SM_Free_Shipping_Bar::init();
    SM_Instagram_Feed::init();
    SM_CSV_Export::init();
    SM_Trust_Counters::init();
    SM_Admin_Settings::init();
    SM_Inventory_Manager::init();
    SM_Product_I18n::init();

    /* ── AJAX: product filter (FR-01.3) ──────────────────────────── */
    add_action( 'wp_ajax_solmaram_filter_products',        'solmaram_ajax_filter_products' );
    add_action( 'wp_ajax_nopriv_solmaram_filter_products', 'solmaram_ajax_filter_products' );

    /* ── Order language: capture at checkout ─────────────────────── */
    add_action( 'woocommerce_checkout_order_processed', function ( int $order_id, array $posted, WC_Order $order ): void {
        $lang = function_exists( 'pll_current_language' ) ? pll_current_language() : 'uk';
        $order->update_meta_data( '_sm_order_lang', sanitize_key( $lang ) );
        $order->save();
    }, 10, 3 );

    /* ── Orders list: Language column ───────────────────────────────── */
    // HPOS orders screen
    add_filter( 'manage_woocommerce_page_wc-orders_columns', function ( array $columns ): array {
        $new = [];
        foreach ( $columns as $key => $label ) {
            $new[ $key ] = $label;
            if ( $key === 'order_status' ) {
                $new['sm_order_lang'] = __( 'Lang', 'solmaram' );
            }
        }
        return $new;
    } );
    add_action( 'manage_woocommerce_page_wc-orders_custom_column', function ( string $column, WC_Order $order ): void {
        if ( $column === 'sm_order_lang' ) {
            echo esc_html( strtoupper( $order->get_meta( '_sm_order_lang' ) ?: 'UK' ) );
        }
    }, 10, 2 );
    // Legacy CPT orders screen
    add_filter( 'manage_edit-shop_order_columns', function ( array $columns ): array {
        $new = [];
        foreach ( $columns as $key => $label ) {
            $new[ $key ] = $label;
            if ( $key === 'order_status' ) {
                $new['sm_order_lang'] = __( 'Lang', 'solmaram' );
            }
        }
        return $new;
    } );
    add_action( 'manage_shop_order_posts_custom_column', function ( string $column ): void {
        global $post;
        if ( $column === 'sm_order_lang' ) {
            $order = wc_get_order( $post->ID );
            echo esc_html( strtoupper( $order ? ( $order->get_meta( '_sm_order_lang' ) ?: 'UK' ) : 'UK' ) );
        }
    } );
} );

function solmaram_ajax_filter_products() {
    check_ajax_referer( 'sm_filter', 'nonce' );

    // admin-ajax has no language prefix, so Polylang defaults to UA — filtered
    // product names and the "No products found" string come back Ukrainian even on
    // the EN/PT shop. Detect the language from the shop-page referer and apply it.
    if ( function_exists( 'PLL' ) && function_exists( 'pll_default_language' ) && function_exists( 'pll_languages_list' ) ) {
        $referer = $_SERVER['HTTP_REFERER'] ?? '';
        foreach ( pll_languages_list( [ 'fields' => 'slug' ] ) as $slug ) {
            if ( $slug !== pll_default_language() && $referer && strpos( $referer, '/' . $slug . '/' ) !== false ) {
                $lang = PLL()->model->get_language( $slug );
                if ( $lang ) {
                    PLL()->curlang = $lang;
                    // Reload textdomains so filtered product names + the "No products
                    // found." string come back localized (admin-ajax has no language).
                    if ( ! empty( $lang->locale ) && function_exists( 'sm_switch_locale_full' ) ) {
                        sm_switch_locale_full( $lang->locale );
                    }
                }
                break;
            }
        }
    }

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
            'taxonomy' => 'sm_use_case',
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
            'value'   => ( $min_price && $max_price ) ? [ $min_price, $max_price ] : ( $min_price ?: $max_price ),
            'compare' => ( $min_price && $max_price ) ? 'BETWEEN' : ( $min_price ? '>=' : '<=' ),
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
