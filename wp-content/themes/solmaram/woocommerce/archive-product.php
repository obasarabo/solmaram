<?php
/**
 * Product catalogue with AJAX filters (FR-01.3)
 */
defined( 'ABSPATH' ) || exit;

get_header();
?>

<main id="main" class="site-main shop-page">
  <div class="container shop-layout">

    <!-- Filter sidebar -->
    <aside class="shop-filters" id="shop-filters">
      <div class="shop-filters__inner">
        <h2 class="shop-filters__title"><?php esc_html_e( 'Filter', 'solmaram' ); ?></h2>

        <!-- Category -->
        <div class="filter-group">
          <h3 class="filter-group__label"><?php esc_html_e( 'Category', 'solmaram' ); ?></h3>
          <?php
          $categories = get_terms( [
              'taxonomy'   => 'product_cat',
              'hide_empty' => true,
              'parent'     => 0,
              'exclude'    => [ get_option( 'default_product_cat' ) ],
          ] );
          if ( is_wp_error( $categories ) ) $categories = [];
          foreach ( $categories as $cat ) :
              $checked = in_array( $cat->slug, (array) ( $_GET['product_cat'] ?? [] ), true );
          ?>
            <label class="filter-option">
              <input type="checkbox" name="product_cat[]" value="<?php echo esc_attr( $cat->slug ); ?>"
                     class="js-filter-input" <?php checked( $checked ); ?>>
              <?php echo esc_html( $cat->name ); ?>
              <span class="filter-option__count">(<?php echo absint( $cat->count ); ?>)</span>
            </label>
            <?php
            // Subcategories
            $subcats = get_terms( [ 'taxonomy' => 'product_cat', 'hide_empty' => true, 'parent' => $cat->term_id ] );
            if ( is_wp_error( $subcats ) ) $subcats = [];
            foreach ( $subcats as $sub ) :
                $sub_checked = in_array( $sub->slug, (array) ( $_GET['product_cat'] ?? [] ), true );
            ?>
              <label class="filter-option filter-option--sub">
                <input type="checkbox" name="product_cat[]" value="<?php echo esc_attr( $sub->slug ); ?>"
                       class="js-filter-input" <?php checked( $sub_checked ); ?>>
                <?php echo esc_html( $sub->name ); ?>
              </label>
            <?php endforeach; ?>
          <?php endforeach; ?>
        </div>

        <!-- Use case -->
        <div class="filter-group">
          <h3 class="filter-group__label"><?php esc_html_e( 'Use Case', 'solmaram' ); ?></h3>
          <?php
          $use_cases = [
              'snack'     => __( 'Snack', 'solmaram' ),
              'cooking'   => __( 'Cooking', 'solmaram' ),
              'ready-meal'=> __( 'Ready Meal', 'solmaram' ),
          ];
          foreach ( $use_cases as $slug => $label ) :
              $checked = in_array( $slug, (array) ( $_GET['use_case'] ?? [] ), true );
          ?>
            <label class="filter-option">
              <input type="checkbox" name="use_case[]" value="<?php echo esc_attr( $slug ); ?>"
                     class="js-filter-input" <?php checked( $checked ); ?>>
              <?php echo esc_html( $label ); ?>
            </label>
          <?php endforeach; ?>
        </div>

        <!-- Price range -->
        <div class="filter-group">
          <h3 class="filter-group__label"><?php esc_html_e( 'Price', 'solmaram' ); ?></h3>
          <div class="price-range">
            <label class="sr-only" for="price-min"><?php esc_html_e( 'Min price', 'solmaram' ); ?></label>
            <input type="number" id="price-min" name="min_price" class="js-filter-input price-range__input"
                   placeholder="0" min="0" value="<?php echo absint( $_GET['min_price'] ?? 0 ); ?>">
            <span>–</span>
            <label class="sr-only" for="price-max"><?php esc_html_e( 'Max price', 'solmaram' ); ?></label>
            <input type="number" id="price-max" name="max_price" class="js-filter-input price-range__input"
                   placeholder="<?php esc_attr_e( 'Any', 'solmaram' ); ?>"
                   min="0" value="<?php echo absint( $_GET['max_price'] ?? 0 ) ?: ''; ?>">
          </div>
        </div>

      </div>
    </aside>

    <!-- Product area -->
    <div class="shop-content" id="shop-content">

      <!-- Toolbar: active filter tags + sort -->
      <div class="shop-toolbar">
        <div class="active-filters" id="active-filters"></div>
        <div class="shop-toolbar__sort">
          <label for="sort-select" class="sr-only"><?php esc_html_e( 'Sort by', 'solmaram' ); ?></label>
          <select id="sort-select" class="js-filter-input sort-select" name="orderby">
            <option value="popularity" <?php selected( ( $_GET['orderby'] ?? '' ), 'popularity' ); ?>><?php esc_html_e( 'Most popular', 'solmaram' ); ?></option>
            <option value="date"       <?php selected( ( $_GET['orderby'] ?? '' ), 'date' ); ?>><?php esc_html_e( 'Newest', 'solmaram' ); ?></option>
            <option value="price"      <?php selected( ( $_GET['orderby'] ?? '' ), 'price' ); ?>><?php esc_html_e( 'Price: low to high', 'solmaram' ); ?></option>
            <option value="price-desc" <?php selected( ( $_GET['orderby'] ?? '' ), 'price-desc' ); ?>><?php esc_html_e( 'Price: high to low', 'solmaram' ); ?></option>
          </select>
        </div>
      </div>

      <!-- Products grid (AJAX target) -->
      <div class="products-grid grid-4" id="products-grid">
        <?php
        if ( woocommerce_product_loop() ) {
            while ( have_posts() ) {
                the_post();
                wc_get_template_part( 'content', 'product' );
            }
        } else {
            echo '<p class="no-products">' . esc_html__( 'No products found.', 'solmaram' ) . '</p>';
        }
        ?>
      </div>

      <div class="shop-pagination" id="shop-pagination">
        <?php solmaram_pagination(); ?>
      </div>

    </div><!-- .shop-content -->
  </div><!-- .shop-layout -->
</main>

<?php get_footer(); ?>
