<?php
defined( 'ABSPATH' ) || exit;

class WC_SM_Nova_Poshta_Shipping extends WC_Shipping_Method {

    public function __construct( $instance_id = 0 ) {
        $this->id                 = 'sm_nova_poshta';
        $this->instance_id        = absint( $instance_id );
        $this->method_title       = __( 'Nova Poshta', 'solmaram' );
        $this->method_description = __( 'Real-time shipping via Nova Poshta API — branch pickup or courier delivery.', 'solmaram' );
        $this->supports           = [ 'shipping-zones', 'instance-settings' ];

        $this->init();
    }

    public function init() {
        $this->init_form_fields();
        $this->init_settings();

        $this->enabled = $this->get_option( 'enabled' );
        $this->title   = $this->get_option( 'title', __( 'Nova Poshta', 'solmaram' ) );

        add_action( 'woocommerce_update_options_shipping_' . $this->id, [ $this, 'process_admin_options' ] );
        add_action( 'woocommerce_after_shipping_rate', [ $this, 'render_np_fields' ], 10, 2 );
    }

    public function init_form_fields() {
        $this->instance_form_fields = [
            'enabled'         => [ 'title' => __( 'Enable', 'solmaram' ), 'type' => 'checkbox', 'default' => 'yes' ],
            'title'           => [ 'title' => __( 'Title', 'solmaram' ), 'type' => 'text', 'default' => __( 'Nova Poshta', 'solmaram' ) ],
            'api_key'         => [ 'title' => __( 'API Key', 'solmaram' ), 'type' => 'password', 'default' => '', 'description' => __( 'Your Nova Poshta API key.', 'solmaram' ) ],
            'city_sender_ref' => [ 'title' => __( 'Sender City Ref', 'solmaram' ), 'type' => 'text', 'default' => '' ],
            'fallback_cost'   => [ 'title' => __( 'Fallback Cost (₴)', 'solmaram' ), 'type' => 'number', 'default' => 80 ],
        ];
    }

    public function calculate_shipping( $package = [] ) {
        $api_key    = $this->get_option( 'api_key' ) ?: get_option( 'sm_np_api_key', '' );
        $api        = new SM_Nova_Poshta_API( $api_key );

        // Weight: sum of items (in kg; default 0.3kg per item if not set)
        $weight = 0;
        foreach ( $package['contents'] as $item ) {
            $product = $item['data'];
            $w = (float) $product->get_weight();
            $weight += ( $w > 0 ? $w : 0.3 ) * $item['quantity'];
        }

        $city_recipient_ref = ( WC() && WC()->session ) ? WC()->session->get( 'np_city_ref' ) : '';
        $declared_cost      = (float) WC()->cart->get_subtotal();

        // Branch-to-branch
        $cost_branch = $city_recipient_ref
            ? $api->calculate_shipping( [
                'city_sender_ref'    => $this->get_option( 'city_sender_ref' ),
                'city_recipient_ref' => $city_recipient_ref,
                'weight'             => max( 0.1, $weight ),
                'service_type'       => 'WarehouseWarehouse',
                'declared_cost'      => max( 1, $declared_cost ),
            ] )
            : (float) $this->get_option( 'fallback_cost', 80 );

        $this->add_rate( [
            'id'    => $this->id . '_branch',
            'label' => __( 'Nova Poshta — Branch Pickup', 'solmaram' ),
            'cost'  => $cost_branch,
        ] );

        // Courier
        $cost_courier = $city_recipient_ref
            ? $api->calculate_shipping( [
                'city_sender_ref'    => $this->get_option( 'city_sender_ref' ),
                'city_recipient_ref' => $city_recipient_ref,
                'weight'             => max( 0.1, $weight ),
                'service_type'       => 'WarehouseDoors',
                'declared_cost'      => max( 1, $declared_cost ),
            ] )
            : (float) $this->get_option( 'fallback_cost', 80 ) + 20;

        $this->add_rate( [
            'id'    => $this->id . '_courier',
            'label' => __( 'Nova Poshta — Courier Delivery', 'solmaram' ),
            'cost'  => $cost_courier,
        ] );
    }

    public function render_np_fields( WC_Shipping_Rate $rate, int $index ) {
        if ( strpos( $rate->get_id(), 'sm_nova_poshta' ) === false ) return;
        $is_branch = strpos( $rate->get_id(), '_branch' ) !== false;
        ?>
        <div class="shipping-np-wrapper" id="np-<?php echo esc_attr( $rate->get_id() ); ?>">
          <label class="np-city-label"><?php esc_html_e( 'City', 'solmaram' ); ?></label>
          <div style="position:relative">
            <input type="text" class="np-city-input"
                   placeholder="<?php esc_attr_e( 'Start typing your city…', 'solmaram' ); ?>"
                   autocomplete="off">
          </div>
          <?php if ( $is_branch ) : ?>
            <label><?php esc_html_e( 'Branch', 'solmaram' ); ?></label>
            <select class="np-branch-select" name="np_branch_ref"
                    data-loading="<?php esc_attr_e( 'Select city first…', 'solmaram' ); ?>">
              <option value=""><?php esc_html_e( 'Select city first', 'solmaram' ); ?></option>
            </select>
          <?php else : ?>
            <label><?php esc_html_e( 'Street & Building', 'solmaram' ); ?></label>
            <input type="text" class="np-address-input" name="np_courier_address"
                   placeholder="<?php esc_attr_e( 'Street, building number', 'solmaram' ); ?>">
          <?php endif; ?>
        </div>
        <?php
    }
}
