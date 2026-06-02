<?php
defined( 'ABSPATH' ) || exit;

class SM_CSV_Export {

    public static function init() {
        add_action( 'admin_menu', [ __CLASS__, 'add_export_page' ] );
        add_action( 'admin_init', [ __CLASS__, 'handle_export' ] );
    }

    public static function add_export_page() {
        add_submenu_page(
            'woocommerce',
            __( 'Export Orders CSV', 'solmaram' ),
            __( 'Export Orders', 'solmaram' ),
            'manage_woocommerce',
            'sm-export-orders',
            [ __CLASS__, 'render_page' ]
        );
    }

    public static function render_page() {
        ?>
        <div class="wrap">
          <h1><?php esc_html_e( 'Export Orders to CSV', 'solmaram' ); ?></h1>
          <form method="post">
            <?php wp_nonce_field( 'sm_export_orders', 'sm_export_nonce' ); ?>
            <table class="form-table">
              <tr>
                <th><label for="sm_date_from"><?php esc_html_e( 'From', 'solmaram' ); ?></label></th>
                <td><input type="date" id="sm_date_from" name="sm_date_from"></td>
              </tr>
              <tr>
                <th><label for="sm_date_to"><?php esc_html_e( 'To', 'solmaram' ); ?></label></th>
                <td><input type="date" id="sm_date_to" name="sm_date_to"></td>
              </tr>
              <tr>
                <th><label for="sm_order_status"><?php esc_html_e( 'Status', 'solmaram' ); ?></label></th>
                <td>
                  <select id="sm_order_status" name="sm_order_status">
                    <option value=""><?php esc_html_e( 'All', 'solmaram' ); ?></option>
                    <?php foreach ( wc_get_order_statuses() as $slug => $label ) : ?>
                      <option value="<?php echo esc_attr( $slug ); ?>"><?php echo esc_html( $label ); ?></option>
                    <?php endforeach; ?>
                  </select>
                </td>
              </tr>
            </table>
            <p><button type="submit" name="sm_do_export" class="button button-primary"><?php esc_html_e( 'Download CSV', 'solmaram' ); ?></button></p>
          </form>
        </div>
        <?php
    }

    public static function handle_export() {
        if ( ! isset( $_POST['sm_do_export'] ) ) return;
        if ( ! current_user_can( 'manage_woocommerce' ) ) wp_die( 'Unauthorized' );
        if ( ! wp_verify_nonce( $_POST['sm_export_nonce'] ?? '', 'sm_export_orders' ) ) wp_die( 'Invalid nonce' );

        $args = [
            'limit'    => -1,
            'type'     => 'shop_order',
            'orderby'  => 'date',
            'order'    => 'DESC',
        ];

        $date_from = sanitize_text_field( $_POST['sm_date_from'] ?? '' );
        $date_to   = sanitize_text_field( $_POST['sm_date_to'] ?? '' );
        $status    = sanitize_text_field( $_POST['sm_order_status'] ?? '' );

        if ( $date_from && $date_to ) {
            $args['date_created'] = $date_from . '...' . $date_to;
        } elseif ( $date_from ) {
            $args['date_created'] = '>=' . $date_from;
        } elseif ( $date_to ) {
            $args['date_created'] = '<=' . $date_to;
        }
        if ( $status )    $args['status'] = $status;

        $orders = wc_get_orders( $args );

        header( 'Content-Type: text/csv; charset=UTF-8' );
        header( 'Content-Disposition: attachment; filename=solmaram-orders-' . gmdate( 'Y-m-d' ) . '.csv' );
        header( 'Pragma: no-cache' );

        $fp = fopen( 'php://output', 'wb' );
        // UTF-8 BOM for Excel
        fprintf( $fp, chr( 0xEF ) . chr( 0xBB ) . chr( 0xBF ) );

        fputcsv( $fp, [
            __( 'Order #', 'solmaram' ),
            __( 'Date', 'solmaram' ),
            __( 'Status', 'solmaram' ),
            __( 'Customer', 'solmaram' ),
            __( 'Email', 'solmaram' ),
            __( 'Phone', 'solmaram' ),
            __( 'Items', 'solmaram' ),
            __( 'Subtotal', 'solmaram' ),
            __( 'Shipping', 'solmaram' ),
            __( 'Total', 'solmaram' ),
            __( 'Payment', 'solmaram' ),
            __( 'Shipping Method', 'solmaram' ),
        ] );

        foreach ( $orders as $order ) {
            $items = [];
            foreach ( $order->get_items() as $item ) {
                $items[] = $item->get_name() . ' ×' . $item->get_quantity();
            }
            $shipping_lines = $order->get_shipping_methods();
            $shipping_name  = $shipping_lines ? current( $shipping_lines )->get_method_title() : '';

            fputcsv( $fp, [
                $order->get_id(),
                $order->get_date_created()?->date( 'Y-m-d H:i' ),
                wc_get_order_status_name( $order->get_status() ),
                $order->get_formatted_billing_full_name(),
                $order->get_billing_email(),
                $order->get_billing_phone(),
                implode( '; ', $items ),
                $order->get_subtotal(),
                $order->get_shipping_total(),
                $order->get_total(),
                $order->get_payment_method_title(),
                $shipping_name,
            ] );
        }
        fclose( $fp );
        exit;
    }
}
