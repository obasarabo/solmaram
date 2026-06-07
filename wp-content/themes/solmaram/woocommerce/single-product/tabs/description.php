<?php
/**
 * Single product description tab content (FR-01.2)
 */
defined( 'ABSPATH' ) || exit;

global $post, $product;

$heading = apply_filters( 'woocommerce_product_description_heading', __( 'Product Description', 'solmaram' ) );
?>
<div id="tab-description" class="woocommerce-Tabs-panel product-tab-description" role="tabpanel">

  <?php if ( $heading ) : ?>
    <h2><?php echo esc_html( $heading ); ?></h2>
  <?php endif; ?>

  <?php the_content(); ?>

  <?php
  // Use cases block (FR-01.2)
  $relevant = wp_get_post_terms( $product->get_id(), 'sm_use_case' );
  if ( is_wp_error( $relevant ) ) $relevant = [];
  if ( ! empty( $relevant ) ) :
  ?>
  <div class="product-use-cases">
    <h3><?php esc_html_e( 'Ideal for', 'solmaram' ); ?></h3>
    <ul>
      <?php foreach ( $relevant as $use ) : ?>
        <li><?php echo esc_html( $use->name ); ?></li>
      <?php endforeach; ?>
    </ul>
  </div>
  <?php endif; ?>

</div>
