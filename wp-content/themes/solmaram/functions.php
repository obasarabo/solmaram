<?php
defined( 'ABSPATH' ) || exit;

/* ── Theme setup ───────────────────────────────────────────────────── */
add_action( 'after_setup_theme', function () {
    load_theme_textdomain( 'solmaram', get_template_directory() . '/languages' );

    add_theme_support( 'title-tag' );
    add_theme_support( 'post-thumbnails' );
    add_theme_support( 'html5', [ 'search-form', 'comment-form', 'comment-list', 'gallery', 'caption', 'style', 'script' ] );
    add_theme_support( 'customize-selective-refresh-widgets' );
    add_theme_support( 'align-wide' );

    // WooCommerce
    add_theme_support( 'woocommerce', [
        'thumbnail_image_width' => 600,
        'gallery_thumbnail_image_width' => 100,
        'product_grid' => [ 'default_rows' => 3, 'min_rows' => 1, 'default_columns' => 4, 'min_columns' => 1, 'max_columns' => 4 ],
    ] );
    add_theme_support( 'wc-product-gallery-zoom' );
    add_theme_support( 'wc-product-gallery-lightbox' );
    add_theme_support( 'wc-product-gallery-slider' );

    // Menus
    register_nav_menus( [
        'primary'  => __( 'Primary Navigation', 'solmaram' ),
        'footer-1' => __( 'Footer – Company', 'solmaram' ),
        'footer-2' => __( 'Footer – Shop', 'solmaram' ),
    ] );

    // Image sizes
    add_image_size( 'product-card',   600, 600, true );
    add_image_size( 'product-thumb',  100, 100, true );
    add_image_size( 'blog-card',      800, 500, true );
    add_image_size( 'hero-full',     1920, 800, true );
} );

/* ── Enqueue assets ────────────────────────────────────────────────── */
add_action( 'wp_enqueue_scripts', function () {
    $theme_uri = get_template_directory_uri();
    $ver       = wp_get_theme()->get( 'Version' );

    // Google Fonts
    wp_enqueue_style(
        'solmaram-fonts',
        'https://fonts.googleapis.com/css2?family=Inter:wght@400;600&family=Playfair+Display:wght@700&display=swap',
        [],
        null
    );

    wp_enqueue_style( 'solmaram-style', get_stylesheet_uri(), [ 'solmaram-fonts' ], $ver );
    wp_enqueue_style( 'solmaram-main',  $theme_uri . '/assets/css/main.css', [ 'solmaram-style' ], $ver );

    if ( class_exists( 'WooCommerce' ) ) {
        wp_enqueue_style( 'solmaram-woo', $theme_uri . '/assets/css/woocommerce.css', [ 'solmaram-main' ], $ver );
    }

    wp_enqueue_script( 'solmaram-main', $theme_uri . '/assets/js/main.js', [], $ver, true );

    if ( is_shop() || is_product_category() || is_product_tag() ) {
        wp_enqueue_script( 'solmaram-ajax-filters', $theme_uri . '/assets/js/ajax-filters.js', [ 'jquery' ], $ver, true );
        wp_localize_script( 'solmaram-ajax-filters', 'smFilters', [
            'ajaxUrl' => admin_url( 'admin-ajax.php' ),
            'nonce'   => wp_create_nonce( 'sm_filter' ),
        ] );
    }

    if ( is_cart() || is_checkout() || is_shop() || is_product_category() || is_singular( 'product' ) ) {
        wp_enqueue_script( 'solmaram-mini-cart', $theme_uri . '/assets/js/mini-cart.js', [ 'jquery', 'wc-cart-fragments' ], $ver, true );
        wp_enqueue_script( 'solmaram-shipping-bar', $theme_uri . '/assets/js/shipping-bar.js', [ 'jquery' ], $ver, true );
    }

    if ( is_checkout() ) {
        wp_enqueue_script( 'solmaram-nova-poshta', $theme_uri . '/assets/js/nova-poshta.js', [ 'jquery' ], $ver, true );
        wp_localize_script( 'solmaram-nova-poshta', 'smNP', [
            'ajaxUrl' => admin_url( 'admin-ajax.php' ),
            'nonce'   => wp_create_nonce( 'sm_np' ),
        ] );
    }
} );

/* ── Customizer settings ───────────────────────────────────────────── */
add_action( 'customize_register', function ( $wp_customize ) {
    // Hero section
    $wp_customize->add_section( 'sm_hero', [ 'title' => __( 'Hero Section', 'solmaram' ), 'priority' => 30 ] );
    foreach ( [
        'sm_hero_tagline'   => [ 'label' => __( 'Tagline', 'solmaram' ),   'default' => __( 'Pure Nature, Maximum Nutrition', 'solmaram' ) ],
        'sm_hero_cta_label' => [ 'label' => __( 'CTA Label', 'solmaram' ), 'default' => __( 'Shop Now', 'solmaram' ) ],
        'sm_hero_cta_url'   => [ 'label' => __( 'CTA URL', 'solmaram' ),   'default' => '/shop/' ],
    ] as $id => $args ) {
        $wp_customize->add_setting( $id, [ 'default' => $args['default'], 'sanitize_callback' => 'sanitize_text_field' ] );
        $wp_customize->add_control( $id, [ 'label' => $args['label'], 'section' => 'sm_hero', 'type' => 'text' ] );
    }
    $wp_customize->add_setting( 'sm_hero_image', [ 'sanitize_callback' => 'absint' ] );
    $wp_customize->add_control( new WP_Customize_Media_Control( $wp_customize, 'sm_hero_image', [
        'label'     => __( 'Hero Image', 'solmaram' ),
        'section'   => 'sm_hero',
        'mime_type' => 'image',
    ] ) );

    // Freeze-drying teaser image
    $wp_customize->add_section( 'sm_fd', [ 'title' => __( 'Freeze-Drying Teaser', 'solmaram' ), 'priority' => 33 ] );
    $wp_customize->add_setting( 'sm_fd_image', [ 'sanitize_callback' => 'absint' ] );
    $wp_customize->add_control( new WP_Customize_Media_Control( $wp_customize, 'sm_fd_image', [
        'label'     => __( 'Freeze-Drying Photo', 'solmaram' ),
        'section'   => 'sm_fd',
        'mime_type' => 'image',
    ] ) );

    // About us section
    $wp_customize->add_section( 'sm_about', [ 'title' => __( 'About Us Section', 'solmaram' ), 'priority' => 35 ] );
    $wp_customize->add_setting( 'sm_about_text', [ 'default' => '', 'sanitize_callback' => 'wp_kses_post' ] );
    $wp_customize->add_control( 'sm_about_text', [ 'label' => __( 'About Text', 'solmaram' ), 'section' => 'sm_about', 'type' => 'textarea' ] );
    $wp_customize->add_setting( 'sm_about_image', [ 'sanitize_callback' => 'absint' ] );
    $wp_customize->add_control( new WP_Customize_Media_Control( $wp_customize, 'sm_about_image', [
        'label'     => __( 'Production Photo', 'solmaram' ),
        'section'   => 'sm_about',
        'mime_type' => 'image',
    ] ) );
} );

/* ── WooCommerce: remove default wrappers ──────────────────────────── */
remove_action( 'woocommerce_before_main_content', 'woocommerce_output_content_wrapper' );
remove_action( 'woocommerce_after_main_content', 'woocommerce_output_content_wrapper_end' );

add_action( 'woocommerce_before_main_content', function () {
    echo '<main id="main" class="site-main woo-main"><div class="container">';
} );
add_action( 'woocommerce_after_main_content', function () {
    echo '</div></main>';
} );

/* ── WooCommerce: disable sidebar breadcrumb ───────────────────────── */
remove_action( 'woocommerce_before_main_content', 'woocommerce_breadcrumb', 20 );

/* ── Excerpt length ────────────────────────────────────────────────── */
add_filter( 'excerpt_length', fn() => 25 );

/* ── Body classes ──────────────────────────────────────────────────── */
add_filter( 'body_class', function ( $classes ) {
    if ( is_singular( 'product' ) ) $classes[] = 'single-product-page';
    if ( is_front_page() )          $classes[] = 'home-page';
    return $classes;
} );

/* ── Polylang: translate WooCommerce page IDs per language (FR-03/04) ─ */
// Without Polylang for WooCommerce, WC options always store the default-language
// page IDs. Filter each option so WC recognises the translated pages (e.g. the
// EN shop archive renders correctly instead of as a blank page).
// IDs are captured once at init before the filters run to avoid recursion.
add_action( 'init', function () {
    if ( is_admin() ) {
        return;
    }
    if ( ! function_exists( 'pll_current_language' ) || ! function_exists( 'pll_get_post' ) ) {
        return;
    }
    $wc_page_options = [
        'woocommerce_shop_page_id',
        'woocommerce_cart_page_id',
        'woocommerce_checkout_page_id',
        'woocommerce_myaccount_page_id',
    ];
    foreach ( $wc_page_options as $option ) {
        $default_id = (int) get_option( $option ); // capture before filter is added
        if ( ! $default_id ) {
            continue;
        }
        add_filter( "pre_option_{$option}", function () use ( $default_id ) {
            $lang          = pll_current_language();
            $translated_id = $lang ? (int) pll_get_post( $default_id, $lang ) : 0;
            return $translated_id ?: $default_id;
        } );
    }
} );

// For non-default languages, convert the translated shop page query into a
// product archive so WooCommerce renders the product grid instead of a blank page.
// WooCommerce's own rewrite rules only cover the default-language shop page.
add_action( 'pre_get_posts', function ( $q ) {
    if ( is_admin() || ! $q->is_main_query() ) {
        return;
    }
    if ( ! function_exists( 'pll_current_language' ) || ! function_exists( 'pll_default_language' ) ) {
        return;
    }
    if ( pll_current_language() === pll_default_language() ) {
        return; // default language handled natively by WooCommerce
    }
    $shop_id            = (int) get_option( 'woocommerce_shop_page_id' ); // already filtered to current lang
    if ( ! $shop_id || ! $q->is_page( $shop_id ) ) {
        return;
    }
    $q->set( 'post_type', 'product' );
    $q->set( 'page_id', '' );
    $q->is_page             = false;
    $q->is_singular         = false;
    $q->is_post_type_archive = true;
    $q->is_archive          = true;
}, 11 ); // after WooCommerce's own pre_get_posts at 10

/* ── hreflang for Polylang (FR-12) ────────────────────────────────── */
add_action( 'wp_head', function () {
    if ( function_exists( 'pll_the_languages' ) ) {
        $languages = pll_the_languages( [ 'raw' => 1 ] );
        foreach ( $languages as $lang ) {
            printf(
                '<link rel="alternate" hreflang="%s" href="%s">' . "\n",
                esc_attr( $lang['slug'] ),
                esc_url( $lang['url'] )
            );
        }
    }
}, 1 );

/* ── Pagination helper ─────────────────────────────────────────────── */
function solmaram_pagination( $query = null ) {
    global $wp_query;
    $q = $query ?: $wp_query;
    echo paginate_links( [
        'total'   => $q->max_num_pages,
        'current' => max( 1, get_query_var( 'paged' ) ),
        'prev_text' => '&lsaquo;',
        'next_text' => '&rsaquo;',
    ] );
}
