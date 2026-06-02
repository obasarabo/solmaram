<?php
defined( 'ABSPATH' ) || exit;

class SM_Trust_Counters {

    public static function init() {
        add_shortcode( 'sm_trust_counters', [ __CLASS__, 'shortcode' ] );
    }

    public static function shortcode(): string {
        $counters = [
            [
                'value' => get_option( 'sm_counter_customers', '10,000+' ),
                'label' => __( 'Customers Served', 'solmaram' ),
            ],
            [
                'value' => get_option( 'sm_counter_orders', '50,000+' ),
                'label' => __( 'Orders Shipped', 'solmaram' ),
            ],
            [
                'value' => get_option( 'sm_counter_years', '5+' ),
                'label' => __( 'Years of Experience', 'solmaram' ),
            ],
        ];

        $html  = '<div class="trust-counters">';
        foreach ( $counters as $c ) {
            $html .= '<div class="trust-counter">';
            $html .= '<span class="trust-counter__value">' . esc_html( $c['value'] ) . '</span>';
            $html .= '<span class="trust-counter__label">' . esc_html( $c['label'] ) . '</span>';
            $html .= '</div>';
        }
        $html .= '</div>';

        return $html;
    }
}
