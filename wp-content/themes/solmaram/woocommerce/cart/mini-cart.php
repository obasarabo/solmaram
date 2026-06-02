<?php
/**
 * Mini cart slide-in panel content (FR-02.1)
 */
defined( 'ABSPATH' ) || exit;

do_action( 'woocommerce_before_mini_cart' );

if ( ! WC()->cart->is_empty() ) : ?>

  <ul class="mini-cart__items">
    <?php
    do_action( 'woocommerce_before_mini_cart_contents' );

    foreach ( WC()->cart->get_cart() as $cart_item_key => $cart_item ) :
        $_product   = apply_filters( 'woocommerce_cart_item_product', $cart_item['data'], $cart_item, $cart_item_key );
        $product_id = apply_filters( 'woocommerce_cart_item_product_id', $cart_item['product_id'], $cart_item, $cart_item_key );

        if ( ! $_product || ! $_product->exists() || 0 === $cart_item['quantity'] ) continue;

        $product_name    = apply_filters( 'woocommerce_cart_item_name', $_product->get_name(), $cart_item, $cart_item_key );
        $thumbnail       = apply_filters( 'woocommerce_cart_item_thumbnail', $_product->get_image( 'product-thumb' ), $cart_item, $cart_item_key );
        $product_price   = apply_filters( 'woocommerce_cart_item_price', WC()->cart->get_product_price( $_product ), $cart_item, $cart_item_key );
        $product_subtotal= apply_filters( 'woocommerce_cart_item_subtotal', WC()->cart->get_product_subtotal( $_product, $cart_item['quantity'] ), $cart_item, $cart_item_key );
        ?>
        <li class="mini-cart__item <?php echo esc_attr( apply_filters( 'woocommerce_mini_cart_item_class', 'mini_cart_item', $cart_item, $cart_item_key ) ); ?>">
          <?php echo $thumbnail; // phpcs:ignore WordPress.Security.EscapeOutput ?>

          <div class="mini-cart__item-details">
            <a href="<?php echo esc_url( get_permalink( $product_id ) ); ?>" class="mini-cart__item-name">
              <?php echo esc_html( $product_name ); ?>
            </a>
            <div class="mini-cart__item-qty">
              <?php
              // Quantity input
              $quantity_html = sprintf(
                  '<input type="number" class="mini-cart-qty" data-cart-key="%s" value="%d" min="1" step="1">',
                  esc_attr( $cart_item_key ),
                  absint( $cart_item['quantity'] )
              );
              echo $quantity_html; // phpcs:ignore
              ?>
              <span class="mini-cart__item-price"><?php echo wp_kses_post( $product_subtotal ); ?></span>
            </div>
          </div>

          <?php
          echo apply_filters(  // phpcs:ignore
              'woocommerce_cart_item_remove_link',
              sprintf(
                  '<a href="%s" class="mini-cart__remove" aria-label="%s" data-product_id="%s" data-cart_item_key="%s" data-product_sku="%s">×</a>',
                  esc_url( wc_get_cart_remove_url( $cart_item_key ) ),
                  esc_attr__( 'Remove this item', 'solmaram' ),
                  esc_attr( $product_id ),
                  esc_attr( $cart_item_key ),
                  esc_attr( $_product->get_sku() )
              ),
              $cart_item_key
          );
          ?>
        </li>
    <?php endforeach; ?>
  </ul>

  <div class="mini-cart__footer">
    <div class="mini-cart__subtotal">
      <strong><?php esc_html_e( 'Subtotal', 'solmaram' ); ?></strong>
      <span><?php echo WC()->cart->get_cart_subtotal(); // phpcs:ignore ?></span>
    </div>

    <?php do_action( 'woocommerce_widget_shopping_cart_before_buttons' ); ?>

    <div class="mini-cart__actions">
      <a href="<?php echo esc_url( wc_get_cart_url() ); ?>" class="btn btn-outline">
        <?php esc_html_e( 'View Cart', 'solmaram' ); ?>
      </a>
      <a href="<?php echo esc_url( wc_get_checkout_url() ); ?>" class="btn btn-primary">
        <?php esc_html_e( 'Checkout', 'solmaram' ); ?>
      </a>
    </div>

    <?php do_action( 'woocommerce_widget_shopping_cart_after_buttons' ); ?>
  </div>

<?php else : ?>
  <p class="mini-cart__empty"><?php esc_html_e( 'Your cart is empty.', 'solmaram' ); ?></p>
<?php endif; ?>

<?php do_action( 'woocommerce_after_mini_cart' ); ?>
