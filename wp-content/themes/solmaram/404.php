<?php
/**
 * 404 page — suggests popular products (FR-10)
 */
get_header();
?>

<main id="main" class="site-main">
  <div class="container" style="padding-block:80px; text-align:center">
    <h1 style="font-size:6rem; color:var(--color-primary); line-height:1">404</h1>
    <h2 style="font-size:1.5rem; margin-bottom:12px"><?php esc_html_e( 'Page not found', 'solmaram' ); ?></h2>
    <p class="text-muted" style="margin-bottom:32px"><?php esc_html_e( "The page you're looking for doesn't exist. Try our popular products:", 'solmaram' ); ?></p>

    <?php if ( class_exists( 'WooCommerce' ) ) :
        $popular = wc_get_products( [ 'tag' => [ 'best-seller' ], 'limit' => 4 ] );
    ?>
    <div class="grid-4 products-grid" style="margin-bottom:40px">
      <?php foreach ( $popular as $product ) :
          $GLOBALS['post'] = get_post( $product->get_id() ); setup_postdata( $GLOBALS['post'] );
      ?>
        <?php wc_get_template_part( 'content', 'product' ); ?>
      <?php endforeach; wp_reset_postdata(); ?>
    </div>
    <?php endif; ?>

    <a href="<?php echo esc_url( home_url( '/' ) ); ?>" class="btn btn-primary"><?php esc_html_e( 'Back to Home', 'solmaram' ); ?></a>
  </div>
</main>

<?php get_footer(); ?>
