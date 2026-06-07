<?php
/**
 * Multilingual product content.
 *
 * Products are language-agnostic (one post, shared across all Polylang languages).
 * EN and PT translations are stored as postmeta and injected via WooCommerce/WP
 * filter hooks so the rest of the codebase needs no changes.
 *
 * Meta key pattern: _sm_{field}_{lang}
 *   _sm_name_en / _sm_name_pt
 *   _sm_short_description_en / _sm_short_description_pt
 *   _sm_description_en / _sm_description_pt
 */
defined( 'ABSPATH' ) || exit;

class SM_Product_I18n {

    private static array $langs = [ 'en', 'pt' ];

    public static function init(): void {
        // Frontend: intercept WooCommerce getters
        add_filter( 'woocommerce_product_get_name',              [ __CLASS__, 'translate_name' ],              10, 2 );
        add_filter( 'woocommerce_product_get_short_description', [ __CLASS__, 'translate_short_description' ], 10, 2 );
        add_filter( 'woocommerce_product_get_description',       [ __CLASS__, 'translate_description' ],       10, 2 );
        // the_title() is used in content-product.php card template
        add_filter( 'the_title',                                 [ __CLASS__, 'translate_title' ],             10, 2 );

        // Admin: meta box
        add_action( 'add_meta_boxes_product', [ __CLASS__, 'register_meta_box' ] );
        add_action( 'save_post_product',      [ __CLASS__, 'save_meta_box' ], 10, 2 );
    }

    // ── Translation helpers ──────────────────────────────────────────────

    private static function current_lang(): string {
        return function_exists( 'pll_current_language' ) ? (string) pll_current_language() : '';
    }

    private static function translated( string $default, int $post_id, string $field ): string {
        $lang = self::current_lang();
        if ( ! $lang || ! function_exists( 'pll_default_language' ) || $lang === pll_default_language() ) {
            return $default;
        }
        $value = get_post_meta( $post_id, "_sm_{$field}_{$lang}", true );
        return $value !== '' && $value !== false ? $value : $default;
    }

    // ── WooCommerce / WP filters ─────────────────────────────────────────

    public static function translate_name( string $value, WC_Product $product ): string {
        return self::translated( $value, $product->get_id(), 'name' );
    }

    public static function translate_short_description( string $value, WC_Product $product ): string {
        return self::translated( $value, $product->get_id(), 'short_description' );
    }

    public static function translate_description( string $value, WC_Product $product ): string {
        return self::translated( $value, $product->get_id(), 'description' );
    }

    public static function translate_title( string $title, int $post_id ): string {
        if ( get_post_type( $post_id ) !== 'product' ) return $title;
        return self::translated( $title, $post_id, 'name' );
    }

    // ── Admin meta box ───────────────────────────────────────────────────

    public static function register_meta_box(): void {
        add_meta_box(
            'sm_product_translations',
            __( 'Translations (EN / PT)', 'solmaram' ),
            [ __CLASS__, 'render_meta_box' ],
            'product',
            'normal',
            'default'
        );
    }

    public static function render_meta_box( WP_Post $post ): void {
        wp_nonce_field( 'sm_product_i18n', 'sm_product_i18n_nonce' );

        $lang_labels = [ 'en' => 'English', 'pt' => 'Português' ];
        $fields = [
            'name'              => [ 'label' => __( 'Product Name', 'solmaram' ),        'type' => 'text' ],
            'short_description' => [ 'label' => __( 'Short Description', 'solmaram' ),   'type' => 'textarea' ],
            'description'       => [ 'label' => __( 'Full Description', 'solmaram' ),    'type' => 'textarea' ],
        ];
        ?>
        <style>
          .sm-i18n-tabs { display:flex; gap:4px; margin-bottom:12px; }
          .sm-i18n-tab  { padding:4px 12px; cursor:pointer; border:1px solid #c3c4c7; background:#f0f0f1; border-radius:3px 3px 0 0; }
          .sm-i18n-tab.active { background:#fff; border-bottom-color:#fff; }
          .sm-i18n-panel { display:none; } .sm-i18n-panel.active { display:block; }
          .sm-i18n-panel label { display:block; font-weight:600; margin:8px 0 4px; }
          .sm-i18n-panel input[type=text], .sm-i18n-panel textarea { width:100%; }
          .sm-i18n-panel textarea { height:80px; }
        </style>
        <div class="sm-i18n-tabs">
          <?php foreach ( self::$langs as $i => $lang ) : ?>
            <div class="sm-i18n-tab <?php echo $i === 0 ? 'active' : ''; ?>"
                 data-panel="sm-panel-<?php echo esc_attr( $lang ); ?>">
              <?php echo esc_html( $lang_labels[ $lang ] ); ?>
            </div>
          <?php endforeach; ?>
        </div>
        <?php foreach ( self::$langs as $i => $lang ) : ?>
          <div id="sm-panel-<?php echo esc_attr( $lang ); ?>"
               class="sm-i18n-panel <?php echo $i === 0 ? 'active' : ''; ?>">
            <?php foreach ( $fields as $key => $cfg ) :
                $meta_key = "_sm_{$key}_{$lang}";
                $value    = get_post_meta( $post->ID, $meta_key, true );
            ?>
              <label for="<?php echo esc_attr( $meta_key ); ?>">
                <?php echo esc_html( $cfg['label'] ); ?>
              </label>
              <?php if ( $cfg['type'] === 'textarea' ) : ?>
                <textarea id="<?php echo esc_attr( $meta_key ); ?>"
                          name="<?php echo esc_attr( $meta_key ); ?>"><?php echo esc_textarea( $value ); ?></textarea>
              <?php else : ?>
                <input type="text" id="<?php echo esc_attr( $meta_key ); ?>"
                       name="<?php echo esc_attr( $meta_key ); ?>"
                       value="<?php echo esc_attr( $value ); ?>">
              <?php endif; ?>
            <?php endforeach; ?>
          </div>
        <?php endforeach; ?>
        <script>
        document.querySelectorAll('.sm-i18n-tab').forEach(function(tab){
          tab.addEventListener('click', function(){
            document.querySelectorAll('.sm-i18n-tab,.sm-i18n-panel').forEach(function(el){el.classList.remove('active');});
            tab.classList.add('active');
            document.getElementById(tab.dataset.panel).classList.add('active');
          });
        });
        </script>
        <?php
    }

    public static function save_meta_box( int $post_id, WP_Post $post ): void {
        if ( ! isset( $_POST['sm_product_i18n_nonce'] ) ) return;
        if ( ! wp_verify_nonce( $_POST['sm_product_i18n_nonce'], 'sm_product_i18n' ) ) return;
        if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) return;
        if ( ! current_user_can( 'edit_post', $post_id ) ) return;

        $text_fields  = [ 'name' ];
        $html_fields  = [ 'short_description', 'description' ];

        foreach ( self::$langs as $lang ) {
            foreach ( $text_fields as $field ) {
                $key = "_sm_{$field}_{$lang}";
                if ( isset( $_POST[ $key ] ) ) {
                    update_post_meta( $post_id, $key, sanitize_text_field( wp_unslash( $_POST[ $key ] ) ) );
                }
            }
            foreach ( $html_fields as $field ) {
                $key = "_sm_{$field}_{$lang}";
                if ( isset( $_POST[ $key ] ) ) {
                    update_post_meta( $post_id, $key, wp_kses_post( wp_unslash( $_POST[ $key ] ) ) );
                }
            }
        }
    }
}
