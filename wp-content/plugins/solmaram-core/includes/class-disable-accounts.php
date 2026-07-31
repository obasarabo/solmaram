<?php
/**
 * Customer accounts turned off (FR-05 disabled).
 *
 * The storefront is guest-checkout only. This class is the single switch:
 * it makes the My Account page and every one of its endpoints unreachable,
 * turns registration off, and strips the remaining front-end links to them.
 * Enforcement lives in code rather than in wp_options so the behaviour travels
 * with the repo and cannot be re-enabled by accident from the WooCommerce
 * settings screens.
 *
 * Admin login (wp-login.php) and wp-admin are deliberately untouched.
 */
defined( 'ABSPATH' ) || exit;

class SM_Disable_Accounts {

    public static function init(): void {
        // Open registration stays off everywhere — admins still create users in wp-admin.
        add_filter( 'pre_option_users_can_register', '__return_zero' );

        // The storefront-facing WooCommerce toggles. Forced on front-end requests
        // only, so the wp-admin settings screens keep showing the stored values
        // instead of silently lying about them.
        if ( ! is_admin() ) {
            $forced = [
                'woocommerce_enable_guest_checkout'                 => 'yes',
                'woocommerce_enable_myaccount_registration'         => 'no',
                'woocommerce_enable_signup_and_login_from_checkout' => 'no',
                'woocommerce_enable_checkout_login_reminder'        => 'no',
            ];
            foreach ( $forced as $option => $value ) {
                add_filter( "pre_option_{$option}", static fn() => $value );
            }
            add_filter( 'woocommerce_checkout_registration_enabled',  '__return_false' );
            add_filter( 'woocommerce_checkout_registration_required', '__return_false' );
        }

        add_action( 'template_redirect', [ __CLASS__, 'block_account_page' ] );
        add_filter( 'wp_nav_menu_objects', [ __CLASS__, 'filter_menu_items' ] );
    }

    /**
     * Bounce every request for the My Account page — in any language, and
     * including its endpoints (orders, view-order, edit-address, edit-account,
     * lost-password, payment-methods) — back to the home page.
     */
    public static function block_account_page(): void {
        if ( is_admin() || ! function_exists( 'is_account_page' ) ) {
            return;
        }

        // is_account_page() resolves per language via the pre_option filter in the
        // theme; the ID check is the belt-and-braces path if that ever goes away.
        if ( ! is_account_page() && ! in_array( get_queried_object_id(), self::account_page_ids(), true ) ) {
            return;
        }

        wp_safe_redirect( self::home_url(), 302 );
        exit;
    }

    /**
     * Remove nav-menu entries pointing at any translation of the My Account page.
     * The items stay in Appearance → Menus so the change is reversible.
     */
    public static function filter_menu_items( array $items ): array {
        if ( is_admin() ) {
            return $items;
        }

        $account_ids = self::account_page_ids();
        if ( ! $account_ids ) {
            return $items;
        }

        return array_values( array_filter( $items, static function ( $item ) use ( $account_ids ) {
            return ! ( 'page' === $item->object && in_array( (int) $item->object_id, $account_ids, true ) );
        } ) );
    }

    /**
     * Every language's My Account page ID. get_option() returns the current
     * language's page (see the pre_option_woocommerce_myaccount_page_id filter in
     * functions.php); Polylang expands that to the whole translation group.
     *
     * @return int[]
     */
    private static function account_page_ids(): array {
        static $ids = null;

        if ( null !== $ids ) {
            return $ids;
        }

        $ids  = [];
        $base = (int) get_option( 'woocommerce_myaccount_page_id' );

        if ( $base ) {
            $ids[] = $base;
            if ( function_exists( 'pll_get_post_translations' ) ) {
                $ids = array_merge( $ids, array_map( 'intval', pll_get_post_translations( $base ) ) );
            }
        }

        $ids = array_values( array_unique( array_filter( $ids ) ) );

        return $ids;
    }

    private static function home_url(): string {
        return function_exists( 'pll_home_url' ) ? pll_home_url() : home_url( '/' );
    }
}
