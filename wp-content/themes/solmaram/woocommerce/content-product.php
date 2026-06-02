<?php
/**
 * Product card template (FR-01.2)
 */
defined( 'ABSPATH' ) || exit;

global $product;

if ( ! $product || ! $product->is_visible() ) return;
?>
<article <?php wc_product_class( 'product-card', $product ); ?>>

  <a href="<?php the_permalink(); ?>" class="product-card__image-link">
    <?php
    if ( has_post_thumbnail() ) {
        the_post_thumbnail( 'product-card', [
            'class' => 'product-card__img',
            'alt'   => esc_attr( $product->get_name() ),
        ] );
    } else {
        echo wc_placeholder_img( 'product-card' );
    }
    ?>
    <?php if ( ! $product->is_in_stock() ) : ?>
      <span class="product-card__badge product-card__badge--out"><?php esc_html_e( 'Out of stock', 'solmaram' ); ?></span>
    <?php endif; ?>
  </a>

  <div class="product-card__body">

    <h3 class="product-card__title">
      <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
    </h3>

    <?php if ( $product->get_average_rating() ) : ?>
    <div class="product-card__rating">
      <?php
      $rating = $product->get_average_rating();
      $count  = $product->get_rating_count();
      echo wc_get_rating_html( $rating, $count );
      printf(
          '<span class="product-card__rating-count">(%d)</span>',
          absint( $count )
      );
      ?>
    </div>
    <?php endif; ?>

    <p class="product-card__excerpt"><?php echo wp_kses_post( $product->get_short_description() ); ?></p>

    <div class="product-card__footer">
      <span class="product-card__price"><?php echo wp_kses_post( $product->get_price_html() ); ?></span>
      <?php
      woocommerce_template_loop_add_to_cart( [
          'class' => 'btn btn-primary product-card__add',
      ] );
      ?>
    </div>

  </div>
</article>
