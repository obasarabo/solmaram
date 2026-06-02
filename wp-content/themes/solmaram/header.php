<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
<meta charset="<?php bloginfo( 'charset' ); ?>">
<meta name="viewport" content="width=device-width, initial-scale=1">
<link rel="profile" href="https://gmpg.org/xfn/11">
<?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>
<?php wp_body_open(); ?>

<a class="skip-link sr-only" href="#main"><?php esc_html_e( 'Skip to content', 'solmaram' ); ?></a>

<header class="site-header" id="site-header">
  <div class="container header-inner">

    <!-- Logo -->
    <a class="site-logo" href="<?php echo esc_url( home_url( '/' ) ); ?>" rel="home">
      <?php
      if ( has_custom_logo() ) {
          the_custom_logo();
      } else {
          echo '<span class="site-logo__text">' . esc_html( get_bloginfo( 'name' ) ) . '</span>';
      }
      ?>
    </a>

    <!-- Primary navigation -->
    <nav class="site-nav" aria-label="<?php esc_attr_e( 'Primary', 'solmaram' ); ?>">
      <?php
      wp_nav_menu( [
          'theme_location' => 'primary',
          'menu_class'     => 'nav-list',
          'container'      => false,
          'fallback_cb'    => false,
      ] );
      ?>
    </nav>

    <!-- Header actions -->
    <div class="header-actions">

      <!-- Search toggle -->
      <button class="header-btn js-search-toggle" aria-label="<?php esc_attr_e( 'Search', 'solmaram' ); ?>" aria-expanded="false">
        <svg width="20" height="20" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" aria-hidden="true">
          <circle cx="11" cy="11" r="8"/><path d="m21 21-4.35-4.35"/>
        </svg>
      </button>

      <?php if ( class_exists( 'WooCommerce' ) ) : ?>

        <!-- My Account -->
        <a class="header-btn" href="<?php echo esc_url( wc_get_account_endpoint_url( 'dashboard' ) ); ?>" aria-label="<?php esc_attr_e( 'My Account', 'solmaram' ); ?>">
          <svg width="20" height="20" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" aria-hidden="true">
            <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/>
          </svg>
        </a>

        <!-- Cart -->
        <a class="header-btn cart-btn js-mini-cart-trigger" href="<?php echo esc_url( wc_get_cart_url() ); ?>" aria-label="<?php esc_attr_e( 'Cart', 'solmaram' ); ?>">
          <svg width="20" height="20" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" aria-hidden="true">
            <path d="M6 2 3 6v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V6l-3-4z"/><line x1="3" y1="6" x2="21" y2="6"/><path d="M16 10a4 4 0 0 1-8 0"/>
          </svg>
          <span class="cart-count"><?php echo esc_html( WC()->cart ? WC()->cart->get_cart_contents_count() : 0 ); ?></span>
        </a>

      <?php endif; ?>

      <!-- Mobile hamburger -->
      <button class="header-btn hamburger js-nav-toggle" aria-label="<?php esc_attr_e( 'Menu', 'solmaram' ); ?>" aria-expanded="false">
        <span></span><span></span><span></span>
      </button>

    </div><!-- .header-actions -->
  </div><!-- .header-inner -->

  <!-- Inline search bar -->
  <div class="header-search" id="header-search" hidden>
    <div class="container">
      <?php get_search_form(); ?>
    </div>
  </div>
</header>

<?php if ( class_exists( 'WooCommerce' ) ) : ?>
<!-- Mini cart slide-in panel (FR-02.1) -->
<div class="mini-cart-overlay js-mini-cart-overlay" aria-hidden="true"></div>
<aside class="mini-cart js-mini-cart" aria-label="<?php esc_attr_e( 'Shopping cart', 'solmaram' ); ?>" aria-hidden="true">
  <div class="mini-cart__header">
    <h2 class="mini-cart__title"><?php esc_html_e( 'Your Cart', 'solmaram' ); ?></h2>
    <button class="mini-cart__close js-mini-cart-close" aria-label="<?php esc_attr_e( 'Close cart', 'solmaram' ); ?>">
      <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true">
        <path d="M18 6 6 18M6 6l12 12"/>
      </svg>
    </button>
  </div>
  <div class="mini-cart__body">
    <?php woocommerce_mini_cart(); ?>
  </div>
</aside>
<?php endif; ?>
