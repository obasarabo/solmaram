<?php
/**
 * Single product meta — SKU + category links (filtered shop, not archive URLs).
 */
defined( 'ABSPATH' ) || exit;

global $product;

$shop_url = wc_get_page_permalink( 'shop' );
$cats     = get_terms( [
    'taxonomy'   => 'sm_use_case',
    'object_ids' => [ $product->get_id() ],
    'hide_empty' => false,
    'lang'       => '',
] );
$product_cats = get_terms( [
    'taxonomy'   => 'product_cat',
    'object_ids' => [ $product->get_id() ],
    'hide_empty' => false,
    'lang'       => '',
    'exclude'    => [ get_option( 'default_product_cat' ) ],
] );
?>
<div class="product_meta">
  <?php do_action( 'woocommerce_product_meta_start' ); ?>

  <?php if ( wc_product_sku_enabled() && ( $product->get_sku() || $product->is_type( 'variable' ) ) ) : ?>
    <span class="sku_wrapper">
      <?php esc_html_e( 'SKU:', 'woocommerce' ); ?>
      <span class="sku"><?php echo $product->get_sku() ?: esc_html__( 'N/A', 'woocommerce' ); ?></span>
    </span>
  <?php endif; ?>

  <?php if ( ! is_wp_error( $product_cats ) && $product_cats ) : ?>
    <span class="posted_in">
      <?php echo esc_html( _n( 'Category:', 'Categories:', count( $product_cats ), 'woocommerce' ) ); ?>
      <?php foreach ( $product_cats as $i => $cat ) : ?>
        <?php if ( $i ) echo ', '; ?>
        <a href="<?php echo esc_url( add_query_arg( 'product_cat', $cat->slug, $shop_url ) ); ?>">
          <?php echo esc_html( $cat->name ); ?>
        </a>
      <?php endforeach; ?>
    </span>
  <?php endif; ?>

  <?php do_action( 'woocommerce_product_meta_end' ); ?>
</div>
