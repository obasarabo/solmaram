<?php
/**
 * Blog/recipe single post (FR-08)
 */
get_header();
?>

<main id="main" class="site-main">
  <div class="container" style="padding-block:48px">
    <div style="max-width:720px; margin-inline:auto">

      <?php while ( have_posts() ) : the_post(); ?>

        <?php
        $is_recipe = has_category( 'recipes' );
        $prep_time = get_post_meta( get_the_ID(), '_recipe_prep_time', true );
        $servings  = get_post_meta( get_the_ID(), '_recipe_servings', true );
        $ingredients = get_post_meta( get_the_ID(), '_recipe_ingredients', true );
        $steps       = get_post_meta( get_the_ID(), '_recipe_steps', true );
        $product_ids = get_post_meta( get_the_ID(), '_recipe_products', true );
        ?>

        <article <?php post_class(); ?>>
          <header class="entry-header">
            <div class="blog-card__meta text-muted" style="margin-bottom:12px">
              <?php the_category( ' · ' ); ?>
              <span>·</span>
              <time datetime="<?php echo esc_attr( get_the_date( 'c' ) ); ?>"><?php the_date(); ?></time>
            </div>
            <h1 style="font-size:clamp(1.6rem,4vw,2.5rem); margin-bottom:16px"><?php the_title(); ?></h1>
            <?php if ( has_post_thumbnail() ) : ?>
              <?php the_post_thumbnail( 'large', [ 'style' => 'width:100%;border-radius:var(--radius-lg);margin-bottom:32px' ] ); ?>
            <?php endif; ?>
          </header>

          <?php if ( $is_recipe && ( $prep_time || $servings ) ) : ?>
          <div class="recipe-meta">
            <?php if ( $prep_time ) : ?>
              <div class="recipe-meta__item">
                <span class="recipe-meta__label"><?php esc_html_e( 'Prep time', 'solmaram' ); ?></span>
                <span><?php echo esc_html( $prep_time ); ?></span>
              </div>
            <?php endif; ?>
            <?php if ( $servings ) : ?>
              <div class="recipe-meta__item">
                <span class="recipe-meta__label"><?php esc_html_e( 'Servings', 'solmaram' ); ?></span>
                <span><?php echo esc_html( $servings ); ?></span>
              </div>
            <?php endif; ?>
          </div>
          <?php endif; ?>

          <?php if ( $is_recipe && $ingredients ) : ?>
          <section style="margin-bottom:32px">
            <h2 style="font-size:1.2rem; margin-bottom:12px"><?php esc_html_e( 'Ingredients', 'solmaram' ); ?></h2>
            <ul style="padding-left:20px; line-height:2">
              <?php foreach ( (array) $ingredients as $item ) : ?>
                <li><?php echo esc_html( $item ); ?></li>
              <?php endforeach; ?>
            </ul>
          </section>
          <?php endif; ?>

          <div class="entry-content">
            <?php the_content(); ?>
          </div>

          <?php if ( $is_recipe && $steps ) : ?>
          <section style="margin-top:32px">
            <h2 style="font-size:1.2rem; margin-bottom:12px"><?php esc_html_e( 'Steps', 'solmaram' ); ?></h2>
            <ol style="padding-left:20px; line-height:2">
              <?php foreach ( (array) $steps as $step ) : ?>
                <li><?php echo wp_kses_post( $step ); ?></li>
              <?php endforeach; ?>
            </ol>
          </section>
          <?php endif; ?>

          <?php if ( $is_recipe && ! empty( $product_ids ) && class_exists( 'WooCommerce' ) ) : ?>
          <div class="recipe-products">
            <h2 style="font-size:1.1rem; margin-bottom:20px"><?php esc_html_e( 'Used in This Recipe', 'solmaram' ); ?></h2>
            <div class="grid-4">
              <?php foreach ( (array) $product_ids as $pid ) :
                  $product = wc_get_product( absint( $pid ) );
                  if ( ! $product || ! $product->is_visible() ) continue;
              ?>
                <a href="<?php echo esc_url( get_permalink( $pid ) ); ?>" class="product-card" style="text-decoration:none">
                  <?php echo $product->get_image( 'product-card' ); // phpcs:ignore ?>
                  <div class="product-card__body">
                    <h3 class="product-card__title" style="font-family:var(--font-body)"><?php echo esc_html( $product->get_name() ); ?></h3>
                    <span class="product-card__price"><?php echo wp_kses_post( $product->get_price_html() ); ?></span>
                  </div>
                </a>
              <?php endforeach; ?>
            </div>
          </div>
          <?php endif; ?>

        </article>

        <?php
        // Related posts
        $related = new WP_Query( [
            'posts_per_page'      => 3,
            'post__not_in'        => [ get_the_ID() ],
            'category__in'        => wp_get_post_categories( get_the_ID() ),
            'no_found_rows'       => true,
            'ignore_sticky_posts' => true,
        ] );
        ?>
        <?php if ( $related->have_posts() ) : ?>
        <section style="margin-top:60px; padding-top:40px; border-top:1px solid var(--color-border)">
          <h2 style="font-size:1.1rem; margin-bottom:24px"><?php esc_html_e( 'Related Articles', 'solmaram' ); ?></h2>
          <div class="grid-3 blog-grid">
            <?php while ( $related->have_posts() ) : $related->the_post(); ?>
              <article class="blog-card">
                <?php if ( has_post_thumbnail() ) : ?>
                  <a class="blog-card__image" href="<?php the_permalink(); ?>"><?php the_post_thumbnail( 'blog-card' ); ?></a>
                <?php endif; ?>
                <div class="blog-card__body">
                  <h3 class="blog-card__title"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h3>
                </div>
              </article>
            <?php endwhile; wp_reset_postdata(); ?>
          </div>
        </section>
        <?php endif; ?>

        <?php comments_template(); ?>

      <?php endwhile; ?>
    </div>
  </div>
</main>

<?php get_footer(); ?>
