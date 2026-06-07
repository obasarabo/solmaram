<?php
/**
 * Single product meta — SKU only.
 * Category and tag links removed: categories are navigated via the shop
 * filter sidebar, not through direct /product-category/ archive URLs.
 */
defined( 'ABSPATH' ) || exit;

global $product;
?>
<div class="product_meta">
  <?php do_action( 'woocommerce_product_meta_start' ); ?>

  <?php if ( wc_product_sku_enabled() && ( $product->get_sku() || $product->is_type( 'variable' ) ) ) : ?>
    <span class="sku_wrapper">
      <?php esc_html_e( 'SKU:', 'woocommerce' ); ?>
      <span class="sku"><?php echo $product->get_sku() ?: esc_html__( 'N/A', 'woocommerce' ); ?></span>
    </span>
  <?php endif; ?>

  <?php do_action( 'woocommerce_product_meta_end' ); ?>
</div>
