<?php
/**
 * Search results: products + articles in separate sections (FR-10)
 */
get_header();

$search_query = get_search_query();
?>

<main id="main" class="site-main">
  <div class="container" style="padding-block:48px">

    <h1 style="margin-bottom:8px">
      <?php
      printf(
          /* translators: %s: search query */
          esc_html__( 'Search results for: %s', 'solmaram' ),
          '<em>' . esc_html( $search_query ) . '</em>'
      );
      ?>
    </h1>

    <form method="get" action="<?php echo esc_url( home_url( '/' ) ); ?>" style="margin-bottom:40px; display:flex; gap:8px; max-width:480px">
      <input type="search" name="s" class="search-field" value="<?php echo esc_attr( $search_query ); ?>"
             placeholder="<?php esc_attr_e( 'Search again…', 'solmaram' ); ?>" style="flex:1; padding:10px 14px; border:1px solid var(--color-border); border-radius:var(--radius-md)">
      <button type="submit" class="btn btn-primary"><?php esc_html_e( 'Search', 'solmaram' ); ?></button>
    </form>

    <?php if ( class_exists( 'WooCommerce' ) ) :
        // Products section
        $product_results = new WP_Query( [
            's'              => $search_query,
            'post_type'      => 'product',
            'posts_per_page' => 8,
            'post_status'    => 'publish',
        ] );
    ?>
    <section class="search-results-section">
      <h2><?php esc_html_e( 'Products', 'solmaram' ); ?></h2>
      <?php if ( $product_results->have_posts() ) : ?>
        <div class="grid-4 products-grid">
          <?php while ( $product_results->have_posts() ) : $product_results->the_post();
              $product = wc_get_product( get_the_ID() );
              if ( ! $product ) continue;
          ?>
            <?php wc_get_template_part( 'content', 'product' ); ?>
          <?php endwhile; wp_reset_postdata(); ?>
        </div>
      <?php else : ?>
        <p class="text-muted"><?php esc_html_e( 'No products found.', 'solmaram' ); ?></p>
      <?php endif; ?>
    </section>
    <?php endif; ?>

    <?php
    // Blog posts section
    $post_results = new WP_Query( [
        's'              => $search_query,
        'post_type'      => 'post',
        'posts_per_page' => 6,
        'post_status'    => 'publish',
    ] );
    ?>
    <section class="search-results-section">
      <h2><?php esc_html_e( 'Articles & Recipes', 'solmaram' ); ?></h2>
      <?php if ( $post_results->have_posts() ) : ?>
        <div class="grid-3 blog-grid">
          <?php while ( $post_results->have_posts() ) : $post_results->the_post(); ?>
            <article class="blog-card">
              <?php if ( has_post_thumbnail() ) : ?>
                <a class="blog-card__image" href="<?php the_permalink(); ?>"><?php the_post_thumbnail( 'blog-card' ); ?></a>
              <?php endif; ?>
              <div class="blog-card__body">
                <h3 class="blog-card__title"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h3>
                <p class="blog-card__excerpt"><?php the_excerpt(); ?></p>
              </div>
            </article>
          <?php endwhile; wp_reset_postdata(); ?>
        </div>
      <?php else : ?>
        <p class="text-muted"><?php esc_html_e( 'No articles found.', 'solmaram' ); ?></p>
      <?php endif; ?>
    </section>

    <?php if ( ! have_posts() && ! $product_results->have_posts() ) : ?>
    <div class="no-results-suggest">
      <p><?php esc_html_e( 'Nothing matched your search. Try different keywords, or browse our popular products:', 'solmaram' ); ?></p>
      <?php if ( class_exists( 'WooCommerce' ) ) :
          $popular = wc_get_products( [ 'tag' => [ 'best-seller' ], 'limit' => 4 ] );
      ?>
        <div class="grid-4 products-grid" style="margin-top:24px">
          <?php foreach ( $popular as $product ) :
              $GLOBALS['post'] = get_post( $product->get_id() ); setup_postdata( $GLOBALS['post'] );
          ?>
            <?php wc_get_template_part( 'content', 'product' ); ?>
          <?php endforeach; wp_reset_postdata(); ?>
        </div>
      <?php endif; ?>
    </div>
    <?php endif; ?>

  </div>
</main>

<?php get_footer(); ?>
