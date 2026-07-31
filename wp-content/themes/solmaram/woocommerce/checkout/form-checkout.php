<?php
/**
 * Two-step checkout template (FR-02.2)
 */
defined( 'ABSPATH' ) || exit;

if ( ! is_checkout() ) return;

// Show WooCommerce notices
wc_print_notices();
do_action( 'woocommerce_before_checkout_form', $checkout );

// Guest checkout only — customer accounts are disabled (see SM_Disable_Accounts).
?>
<form name="checkout" method="post" class="checkout woocommerce-checkout" action="<?php echo esc_url( wc_get_checkout_url() ); ?>" enctype="multipart/form-data">

  <div class="checkout-steps" aria-label="<?php esc_attr_e( 'Checkout steps', 'solmaram' ); ?>">
    <div class="checkout-step-indicator">
      <button type="button" class="step-btn js-checkout-step-btn is-active" data-step="1">
        <span class="step-btn__num">1</span>
        <span class="step-btn__label"><?php esc_html_e( 'Contact & Delivery', 'solmaram' ); ?></span>
      </button>
      <span class="step-sep" aria-hidden="true">›</span>
      <button type="button" class="step-btn js-checkout-step-btn" data-step="2">
        <span class="step-btn__num">2</span>
        <span class="step-btn__label"><?php esc_html_e( 'Payment', 'solmaram' ); ?></span>
      </button>
    </div>
  </div>

  <div class="checkout-layout">

    <!-- STEP 1: Contact + Delivery -->
    <div class="checkout-step js-checkout-step is-active" data-step="1">

      <div class="checkout-section">
        <h2><?php esc_html_e( 'Contact Details', 'solmaram' ); ?></h2>
        <?php do_action( 'woocommerce_checkout_billing' ); ?>
      </div>

      <div class="checkout-section">
        <h2><?php esc_html_e( 'Delivery', 'solmaram' ); ?></h2>
        <?php do_action( 'woocommerce_checkout_shipping' ); ?>

        <!-- Estimated delivery date -->
        <p class="checkout-delivery-estimate">
          <?php esc_html_e( 'Estimated delivery: 1–3 business days via Nova Poshta', 'solmaram' ); ?>
        </p>
      </div>

      <div class="checkout-section">
        <label for="order_comments" class="checkout-label"><?php esc_html_e( 'Order Comment (optional)', 'solmaram' ); ?></label>
        <textarea id="order_comments" name="order_comments" class="input-text" placeholder="<?php esc_attr_e( 'Notes about your order, e.g. special delivery instructions', 'solmaram' ); ?>"></textarea>
      </div>

      <?php do_action( 'woocommerce_checkout_after_customer_details' ); ?>

      <button type="button" class="btn btn-primary js-checkout-next" data-next-step="2">
        <?php esc_html_e( 'Continue to Payment', 'solmaram' ); ?>
      </button>

    </div><!-- step 1 -->

    <!-- STEP 2: Payment -->
    <div class="checkout-step js-checkout-step" data-step="2" hidden>

      <div class="checkout-section">
        <h2><?php esc_html_e( 'Payment Method', 'solmaram' ); ?></h2>
        <?php do_action( 'woocommerce_checkout_payment' ); ?>
      </div>

      <?php do_action( 'woocommerce_checkout_before_order_review_heading' ); ?>
      <div class="checkout-section checkout-order-review">
        <h2><?php esc_html_e( 'Order Summary', 'solmaram' ); ?></h2>
        <?php do_action( 'woocommerce_checkout_order_review' ); ?>
      </div>

    </div><!-- step 2 -->

  </div><!-- .checkout-layout -->

  <?php do_action( 'woocommerce_checkout_after_order_review' ); ?>

</form>

<?php do_action( 'woocommerce_after_checkout_form', $checkout ); ?>
