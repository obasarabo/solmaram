<?php
defined( 'ABSPATH' ) || exit;

class SM_NP_Address_Saver {

    public static function init() {
        add_action( 'woocommerce_checkout_order_processed', [ __CLASS__, 'save_address' ], 10, 3 );
    }

    public static function save_address( int $order_id, array $posted_data, WC_Order $order ) {
        $customer_id = $order->get_customer_id();
        if ( ! $customer_id ) return;

        // Detect Nova Poshta shipping
        $shipping_items = $order->get_shipping_methods();
        $has_np = false;
        foreach ( $shipping_items as $item ) {
            if ( strpos( $item->get_method_id(), 'sm_nova_poshta' ) !== false ) {
                $has_np = true;
                break;
            }
        }
        if ( ! $has_np ) return;

        // Save branch or courier address to customer meta
        $branch_ref      = sanitize_text_field( wp_unslash( $_POST['np_branch_ref'] ?? '' ) );
        $courier_address = sanitize_text_field( wp_unslash( $_POST['np_courier_address'] ?? '' ) );
        $city            = sanitize_text_field( wp_unslash( $_POST['billing_city'] ?? $posted_data['billing_city'] ?? '' ) );

        if ( $branch_ref ) {
            $np_addresses   = get_user_meta( $customer_id, '_np_saved_addresses', true );
            $np_addresses   = is_array( $np_addresses ) ? $np_addresses : [];
            $entry = [ 'type' => 'branch', 'city' => $city, 'branch_ref' => $branch_ref, 'date' => gmdate( 'Y-m-d' ) ];
            // Avoid duplicates
            foreach ( $np_addresses as $addr ) {
                if ( $addr['branch_ref'] === $branch_ref ) return;
            }
            $np_addresses[] = $entry;
            update_user_meta( $customer_id, '_np_saved_addresses', $np_addresses );
        } elseif ( $courier_address ) {
            $np_addresses   = get_user_meta( $customer_id, '_np_saved_addresses', true );
            $np_addresses   = is_array( $np_addresses ) ? $np_addresses : [];
            $entry = [ 'type' => 'courier', 'city' => $city, 'address' => $courier_address, 'date' => gmdate( 'Y-m-d' ) ];
            foreach ( $np_addresses as $addr ) {
                if ( ( $addr['address'] ?? '' ) === $courier_address ) return;
            }
            $np_addresses[] = $entry;
            update_user_meta( $customer_id, '_np_saved_addresses', $np_addresses );
        }
    }
}
