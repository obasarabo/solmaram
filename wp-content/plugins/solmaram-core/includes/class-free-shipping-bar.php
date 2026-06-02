<?php
defined( 'ABSPATH' ) || exit;

class SM_Free_Shipping_Bar {

    public static function init() {
        add_action( 'woocommerce_before_cart', [ __CLASS__, 'render_bar' ] );
        add_shortcode( 'sm_shipping_bar', [ __CLASS__, 'shortcode' ] );
        add_action( 'wp_enqueue_scripts', [ __CLASS__, 'localize_data' ] );
    }

    public static function get_threshold(): float {
        return (float) get_option( 'sm_free_shipping_threshold', 500 );
    }

    private static function get_subtotal(): float {
        return WC()->cart ? (float) WC()->cart->get_subtotal() : 0.0;
    }

    public static function render_bar( string $context = '' ): string {
        $threshold = self::get_threshold();
        $subtotal  = self::get_subtotal();
        $pct       = min( 100, ( $subtotal / max( 1, $threshold ) ) * 100 );
        $remaining = max( 0, $threshold - $subtotal );
        $complete  = $pct >= 100;

        $label = $complete
            ? esc_html__( '🎉 You have free shipping!', 'solmaram' )
            : sprintf(
                /* translators: %s: money amount */
                esc_html__( 'Add %s more to get free shipping', 'solmaram' ),
                wc_price( $remaining )
              );

        $html  = '<div class="sm-shipping-bar' . ( $complete ? ' sm-shipping-bar--complete' : '' ) . '">';
        $html .= '<p class="sm-shipping-bar__label">' . $label . '</p>';
        $html .= '<div class="sm-shipping-bar__track"><div class="sm-shipping-bar__fill" style="width:' . esc_attr( number_format( $pct, 2 ) ) . '%"></div></div>';
        $html .= '</div>';

        if ( $context === 'shortcode' ) return $html;
        echo $html; // phpcs:ignore WordPress.Security.EscapeOutput
        return '';
    }

    public static function shortcode(): string {
        if ( ! class_exists( 'WooCommerce' ) ) return '';
        return self::render_bar( 'shortcode' );
    }

    public static function localize_data() {
        if ( ! is_cart() && ! is_checkout() ) return;
        wp_localize_script( 'solmaram-shipping-bar', 'smShippingBar', [
            'threshold'      => self::get_threshold(),
            'labelFree'      => esc_html__( '🎉 You have free shipping!', 'solmaram' ),
            'labelRemaining' => esc_html__( 'Add {amount} more to get free shipping', 'solmaram' ),
        ] );
    }
}
