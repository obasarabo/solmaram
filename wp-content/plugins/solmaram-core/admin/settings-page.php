<?php
defined( 'ABSPATH' ) || exit;

class SM_Admin_Settings {

    public static function init() {
        add_action( 'admin_menu', [ __CLASS__, 'add_menu' ] );
        add_action( 'admin_init', [ __CLASS__, 'register_settings' ] );
    }

    public static function add_menu() {
        add_menu_page(
            __( 'SolMaram Settings', 'solmaram' ),
            'SolMaram',
            'manage_options',
            'solmaram-settings',
            [ __CLASS__, 'render_page' ],
            'dashicons-carrot',
            56
        );
    }

    public static function register_settings() {
        $options = [
            'sm_free_shipping_threshold' => 500,
            'sm_instagram_token'         => '',
            'sm_counter_customers'       => '10,000+',
            'sm_counter_orders'          => '50,000+',
            'sm_counter_years'           => '5+',
        ];
        foreach ( $options as $key => $default ) {
            register_setting( 'solmaram_settings', $key, [
                'sanitize_callback' => 'sanitize_text_field',
                'default'           => $default,
            ] );
        }
    }

    public static function render_page() {
        ?>
        <div class="wrap">
          <h1><?php esc_html_e( 'SolMaram Settings', 'solmaram' ); ?></h1>
          <form method="post" action="options.php">
            <?php settings_fields( 'solmaram_settings' ); ?>
            <table class="form-table">
              <tr>
                <th><label for="sm_free_shipping_threshold"><?php esc_html_e( 'Free Shipping Threshold (₴)', 'solmaram' ); ?></label></th>
                <td>
                  <input type="number" id="sm_free_shipping_threshold" name="sm_free_shipping_threshold"
                         value="<?php echo esc_attr( get_option( 'sm_free_shipping_threshold', 500 ) ); ?>" min="0" step="1">
                  <p class="description"><?php esc_html_e( 'Cart subtotal at which free shipping is unlocked.', 'solmaram' ); ?></p>
                </td>
              </tr>
              <tr>
                <th><label for="sm_instagram_token"><?php esc_html_e( 'Instagram Access Token', 'solmaram' ); ?></label></th>
                <td>
                  <input type="text" id="sm_instagram_token" name="sm_instagram_token"
                         value="<?php echo esc_attr( get_option( 'sm_instagram_token', '' ) ); ?>" class="regular-text">
                  <p class="description"><?php esc_html_e( 'Instagram Basic Display API long-lived access token.', 'solmaram' ); ?></p>
                  <button type="button" class="button"
                          onclick="fetch(ajaxurl + '?action=sm_refresh_instagram&_wpnonce=<?php echo esc_js( wp_create_nonce( 'sm_refresh_instagram' ) ); ?>').then(() => alert('Cache cleared'))">
                    <?php esc_html_e( 'Clear Instagram Cache', 'solmaram' ); ?>
                  </button>
                </td>
              </tr>
              <tr>
                <th><label for="sm_counter_customers"><?php esc_html_e( 'Customers Counter Value', 'solmaram' ); ?></label></th>
                <td><input type="text" id="sm_counter_customers" name="sm_counter_customers" value="<?php echo esc_attr( get_option( 'sm_counter_customers', '10,000+' ) ); ?>"></td>
              </tr>
              <tr>
                <th><label for="sm_counter_orders"><?php esc_html_e( 'Orders Counter Value', 'solmaram' ); ?></label></th>
                <td><input type="text" id="sm_counter_orders" name="sm_counter_orders" value="<?php echo esc_attr( get_option( 'sm_counter_orders', '50,000+' ) ); ?>"></td>
              </tr>
              <tr>
                <th><label for="sm_counter_years"><?php esc_html_e( 'Years Counter Value', 'solmaram' ); ?></label></th>
                <td><input type="text" id="sm_counter_years" name="sm_counter_years" value="<?php echo esc_attr( get_option( 'sm_counter_years', '5+' ) ); ?>"></td>
              </tr>
            </table>
            <?php submit_button(); ?>
          </form>
        </div>
        <?php
    }
}
