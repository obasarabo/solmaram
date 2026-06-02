<?php
defined( 'ABSPATH' ) || exit;

class WC_SM_Ukrposhta_Shipping extends WC_Shipping_Method {

    public function __construct( $instance_id = 0 ) {
        $this->id                 = 'sm_ukrposhta';
        $this->instance_id        = absint( $instance_id );
        $this->method_title       = __( 'Ukrposhta', 'solmaram' );
        $this->method_description = __( 'Flat-rate delivery via Ukrposhta — to a branch or home address.', 'solmaram' );
        $this->supports           = [ 'shipping-zones', 'instance-settings' ];

        $this->init();
    }

    public function init() {
        $this->init_form_fields();
        $this->init_settings();

        $this->enabled = $this->get_option( 'enabled' );
        $this->title   = $this->get_option( 'title', __( 'Ukrposhta', 'solmaram' ) );

        add_action( 'woocommerce_update_options_shipping_' . $this->id, [ $this, 'process_admin_options' ] );
    }

    public function init_form_fields() {
        $this->instance_form_fields = [
            'enabled'  => [ 'title' => __( 'Enable', 'solmaram' ), 'type' => 'checkbox', 'default' => 'yes' ],
            'title'    => [ 'title' => __( 'Title', 'solmaram' ), 'type' => 'text', 'default' => __( 'Ukrposhta', 'solmaram' ) ],
            'cost'     => [ 'title' => __( 'Cost (₴)', 'solmaram' ), 'type' => 'number', 'default' => 60, 'description' => __( 'Flat rate for Ukrposhta delivery. Configurable by admin.', 'solmaram' ) ],
            'delivery_type' => [
                'title'   => __( 'Delivery Type', 'solmaram' ),
                'type'    => 'select',
                'default' => 'both',
                'options' => [
                    'branch' => __( 'Branch only', 'solmaram' ),
                    'home'   => __( 'Home address only', 'solmaram' ),
                    'both'   => __( 'Both (customer chooses)', 'solmaram' ),
                ],
            ],
        ];
    }

    public function calculate_shipping( $package = [] ) {
        $cost          = (float) $this->get_option( 'cost', 60 );
        $delivery_type = $this->get_option( 'delivery_type', 'both' );

        if ( in_array( $delivery_type, [ 'branch', 'both' ], true ) ) {
            $this->add_rate( [
                'id'    => $this->id . '_branch',
                'label' => __( 'Ukrposhta — Branch Pickup', 'solmaram' ),
                'cost'  => $cost,
            ] );
        }

        if ( in_array( $delivery_type, [ 'home', 'both' ], true ) ) {
            $this->add_rate( [
                'id'    => $this->id . '_home',
                'label' => __( 'Ukrposhta — Home Delivery', 'solmaram' ),
                'cost'  => $cost,
            ] );
        }
    }
}
