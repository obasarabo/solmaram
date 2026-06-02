<?php
defined( 'ABSPATH' ) || exit;

class SM_Recipe_Meta {

    public static function init() {
        add_action( 'add_meta_boxes', [ __CLASS__, 'add_meta_boxes' ] );
        add_action( 'save_post_post', [ __CLASS__, 'save_meta' ], 10, 2 );
    }

    public static function add_meta_boxes() {
        add_meta_box(
            'sm-recipe-meta',
            __( 'Recipe Details', 'solmaram' ),
            [ __CLASS__, 'render_meta_box' ],
            'post',
            'normal',
            'high'
        );
    }

    public static function render_meta_box( $post ) {
        wp_nonce_field( 'sm_recipe_meta_save', 'sm_recipe_nonce' );

        $prep_time   = get_post_meta( $post->ID, '_recipe_prep_time', true );
        $servings    = get_post_meta( $post->ID, '_recipe_servings', true );
        $ingredients = get_post_meta( $post->ID, '_recipe_ingredients', true );
        $steps       = get_post_meta( $post->ID, '_recipe_steps', true );
        $product_ids = get_post_meta( $post->ID, '_recipe_products', true );
        ?>
        <style>
          .sm-recipe-field { margin-bottom: 16px; }
          .sm-recipe-field label { display: block; font-weight: 600; margin-bottom: 4px; }
          .sm-recipe-field input, .sm-recipe-field textarea { width: 100%; }
          .sm-recipe-field textarea { min-height: 80px; font-family: monospace; font-size: 12px; }
          .sm-recipe-note { color: #666; font-size: 11px; margin-top: 2px; }
        </style>
        <div class="sm-recipe-field">
          <label for="sm_prep_time"><?php esc_html_e( 'Prep Time', 'solmaram' ); ?></label>
          <input type="text" id="sm_prep_time" name="sm_prep_time" value="<?php echo esc_attr( $prep_time ); ?>" placeholder="e.g. 15 min">
        </div>
        <div class="sm-recipe-field">
          <label for="sm_servings"><?php esc_html_e( 'Servings', 'solmaram' ); ?></label>
          <input type="text" id="sm_servings" name="sm_servings" value="<?php echo esc_attr( $servings ); ?>" placeholder="e.g. 2">
        </div>
        <div class="sm-recipe-field">
          <label for="sm_ingredients"><?php esc_html_e( 'Ingredients', 'solmaram' ); ?></label>
          <textarea id="sm_ingredients" name="sm_ingredients"><?php echo esc_textarea( is_array( $ingredients ) ? implode( "\n", $ingredients ) : $ingredients ); ?></textarea>
          <p class="sm-recipe-note"><?php esc_html_e( 'One ingredient per line.', 'solmaram' ); ?></p>
        </div>
        <div class="sm-recipe-field">
          <label for="sm_steps"><?php esc_html_e( 'Steps', 'solmaram' ); ?></label>
          <textarea id="sm_steps" name="sm_steps"><?php echo esc_textarea( is_array( $steps ) ? implode( "\n", $steps ) : $steps ); ?></textarea>
          <p class="sm-recipe-note"><?php esc_html_e( 'One step per line.', 'solmaram' ); ?></p>
        </div>
        <div class="sm-recipe-field">
          <label for="sm_product_ids"><?php esc_html_e( 'Linked Product IDs', 'solmaram' ); ?></label>
          <input type="text" id="sm_product_ids" name="sm_product_ids" value="<?php echo esc_attr( is_array( $product_ids ) ? implode( ', ', $product_ids ) : $product_ids ); ?>" placeholder="e.g. 42, 57, 88">
          <p class="sm-recipe-note"><?php esc_html_e( 'Comma-separated WooCommerce product IDs.', 'solmaram' ); ?></p>
        </div>
        <?php
    }

    public static function save_meta( $post_id, $post ) {
        if ( ! isset( $_POST['sm_recipe_nonce'] ) || ! wp_verify_nonce( $_POST['sm_recipe_nonce'], 'sm_recipe_meta_save' ) ) return;
        if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) return;
        if ( ! current_user_can( 'edit_post', $post_id ) ) return;

        update_post_meta( $post_id, '_recipe_prep_time', sanitize_text_field( $_POST['sm_prep_time'] ?? '' ) );
        update_post_meta( $post_id, '_recipe_servings',  sanitize_text_field( $_POST['sm_servings'] ?? '' ) );

        $ingredients = array_filter( array_map( 'sanitize_text_field', explode( "\n", $_POST['sm_ingredients'] ?? '' ) ) );
        update_post_meta( $post_id, '_recipe_ingredients', $ingredients );

        $steps = array_filter( array_map( 'wp_kses_post', explode( "\n", $_POST['sm_steps'] ?? '' ) ) );
        update_post_meta( $post_id, '_recipe_steps', $steps );

        $product_ids = array_filter( array_map( 'absint', explode( ',', $_POST['sm_product_ids'] ?? '' ) ) );
        update_post_meta( $post_id, '_recipe_products', $product_ids );
    }
}
