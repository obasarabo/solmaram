<?php
/**
 * Inventory lifecycle manager.
 *
 * Tracks three product-level counters:
 *   _stock          (WooCommerce built-in) — available to purchase
 *   _sm_booked_qty  — paid, not yet shipped
 *   _sm_shipped_qty — cumulative units dispatched
 *
 * Order-item meta key _sm_inv_status tracks per-line status: 'booked' | 'shipped'
 */
defined( 'ABSPATH' ) || exit;

class SM_Inventory_Manager {

    public static function init(): void {
        add_action( 'woocommerce_payment_complete',          [ __CLASS__, 'on_payment_complete' ] );
        add_action( 'woocommerce_order_status_wc-shipped',   [ __CLASS__, 'on_order_shipped' ] );
        add_action( 'woocommerce_order_status_cancelled',    [ __CLASS__, 'on_order_cancelled' ] );
        add_action( 'woocommerce_order_status_refunded',     [ __CLASS__, 'on_order_cancelled' ] );

        // Register wc-shipped order status
        add_action( 'init',                                  [ __CLASS__, 'register_shipped_status' ] );
        add_filter( 'wc_order_statuses',                     [ __CLASS__, 'add_shipped_to_statuses' ] );
        add_filter( 'woocommerce_valid_order_statuses_for_payment', [ __CLASS__, 'add_shipped_for_payment' ] );
        add_filter( 'woocommerce_order_is_paid_statuses',    [ __CLASS__, 'add_shipped_to_paid' ] );
    }

    // ── Order status registration ────────────────────────────────────────

    public static function register_shipped_status(): void {
        register_post_status( 'wc-shipped', [
            'label'                     => _x( 'Shipped', 'Order status', 'solmaram' ),
            'public'                    => false,
            'exclude_from_search'       => false,
            'show_in_admin_all_list'    => true,
            'show_in_admin_status_list' => true,
            'label_count'               => _n_noop(
                'Shipped <span class="count">(%s)</span>',
                'Shipped <span class="count">(%s)</span>',
                'solmaram'
            ),
        ] );
    }

    public static function add_shipped_to_statuses( array $statuses ): array {
        $new = [];
        foreach ( $statuses as $key => $label ) {
            $new[ $key ] = $label;
            if ( $key === 'wc-processing' ) {
                $new['wc-shipped'] = _x( 'Shipped', 'Order status', 'solmaram' );
            }
        }
        return $new;
    }

    public static function add_shipped_for_payment( array $statuses ): array {
        $statuses[] = 'shipped';
        return $statuses;
    }

    public static function add_shipped_to_paid( array $statuses ): array {
        $statuses[] = 'shipped';
        return $statuses;
    }

    // ── Inventory hooks ──────────────────────────────────────────────────

    /**
     * Payment confirmed → mark order items as booked, increment _sm_booked_qty.
     * WooCommerce already decrements _stock via wc_reduce_stock_levels().
     */
    public static function on_payment_complete( int $order_id ): void {
        $order = wc_get_order( $order_id );
        if ( ! $order ) return;

        foreach ( $order->get_items() as $item_id => $item ) {
            /** @var WC_Order_Item_Product $item */
            $product_id = $item->get_product_id();
            $qty        = (int) $item->get_quantity();

            self::increment_meta( $product_id, '_sm_booked_qty', $qty );
            wc_update_order_item_meta( $item_id, '_sm_inv_status', 'booked' );
        }
    }

    /**
     * Order marked shipped → move qty from booked to shipped.
     */
    public static function on_order_shipped( int $order_id ): void {
        $order = wc_get_order( $order_id );
        if ( ! $order ) return;

        foreach ( $order->get_items() as $item_id => $item ) {
            /** @var WC_Order_Item_Product $item */
            $inv_status = wc_get_order_item_meta( $item_id, '_sm_inv_status', true );
            if ( $inv_status !== 'booked' ) continue;

            $product_id = $item->get_product_id();
            $qty        = (int) $item->get_quantity();

            self::decrement_meta( $product_id, '_sm_booked_qty', $qty );
            self::increment_meta( $product_id, '_sm_shipped_qty', $qty );
            wc_update_order_item_meta( $item_id, '_sm_inv_status', 'shipped' );
        }
    }

    /**
     * Order cancelled/refunded → release booked qty back (stock restored by WC).
     */
    public static function on_order_cancelled( int $order_id ): void {
        $order = wc_get_order( $order_id );
        if ( ! $order ) return;

        foreach ( $order->get_items() as $item_id => $item ) {
            /** @var WC_Order_Item_Product $item */
            $inv_status = wc_get_order_item_meta( $item_id, '_sm_inv_status', true );
            if ( $inv_status !== 'booked' ) continue;

            $product_id = $item->get_product_id();
            $qty        = (int) $item->get_quantity();

            self::decrement_meta( $product_id, '_sm_booked_qty', $qty );
            wc_update_order_item_meta( $item_id, '_sm_inv_status', 'cancelled' );
        }
    }

    // ── Helpers ──────────────────────────────────────────────────────────

    private static function increment_meta( int $product_id, string $key, int $qty ): void {
        $current = (int) get_post_meta( $product_id, $key, true );
        update_post_meta( $product_id, $key, max( 0, $current + $qty ) );
    }

    private static function decrement_meta( int $product_id, string $key, int $qty ): void {
        $current = (int) get_post_meta( $product_id, $key, true );
        update_post_meta( $product_id, $key, max( 0, $current - $qty ) );
    }
}
