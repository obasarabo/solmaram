<?php
/**
 * My Account page (FR-05)
 */
defined( 'ABSPATH' ) || exit;

$current_user = wp_get_current_user();
?>
<div class="my-account-layout">

  <!-- Sidebar nav -->
  <nav class="my-account-nav" aria-label="<?php esc_attr_e( 'My Account', 'solmaram' ); ?>">
    <?php
    $endpoints = [
        'dashboard'       => __( 'Dashboard', 'solmaram' ),
        'orders'          => __( 'Order History', 'solmaram' ),
        'edit-address'    => __( 'Saved Addresses', 'solmaram' ),
        'edit-account'    => __( 'Profile Settings', 'solmaram' ),
        'customer-logout' => __( 'Log Out', 'solmaram' ),
    ];
    ?>
    <ul class="my-account-nav__list">
      <?php foreach ( $endpoints as $endpoint => $label ) : ?>
        <li class="<?php echo is_wc_endpoint_url( $endpoint ) ? 'is-active' : ''; ?>">
          <a href="<?php echo esc_url( wc_get_account_endpoint_url( $endpoint ) ); ?>">
            <?php echo esc_html( $label ); ?>
          </a>
        </li>
      <?php endforeach; ?>
    </ul>
  </nav>

  <!-- Content area -->
  <div class="my-account-content">
    <?php
    do_action( 'woocommerce_account_content' );
    ?>
  </div>

</div>
