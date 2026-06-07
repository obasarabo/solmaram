<?php get_header(); ?>

<main id="main" class="site-main">

  <?php /* ── 1. Hero ─────────────────────────────────────────────── */ ?>
  <?php
  $hero_image_id  = get_theme_mod( 'sm_hero_image' );
  $hero_image_url = $hero_image_id ? wp_get_attachment_image_url( $hero_image_id, 'hero-full' ) : '';
  ?>
  <section class="hero" style="<?php echo $hero_image_url ? 'background-image:url(' . esc_url( $hero_image_url ) . ')' : ''; ?>">
    <div class="container hero__inner">
      <h1 class="hero__heading">
        <?php echo esc_html( get_theme_mod( 'sm_hero_tagline', __( 'Pure Nature, Maximum Nutrition', 'solmaram' ) ) ); ?>
      </h1>
      <a href="<?php echo esc_url( get_theme_mod( 'sm_hero_cta_url', wc_get_page_permalink( 'shop' ) ) ); ?>"
         class="btn btn-primary hero__cta">
        <?php echo esc_html( get_theme_mod( 'sm_hero_cta_label', __( 'Shop Now', 'solmaram' ) ) ); ?>
      </a>
    </div>
  </section>

  <?php /* ── 2. Value proposition ─────────────────────────────────── */ ?>
  <section class="section section-values">
    <div class="container">
      <ul class="values-grid">
        <?php
        $values = [
            [ 'img_id' => 136, 'label' => __( '100% Natural',      'solmaram' ) ],
            [ 'img_id' => 137, 'label' => __( 'Rich in Vitamins',  'solmaram' ) ],
            [ 'img_id' => 138, 'label' => __( 'No Preservatives',  'solmaram' ) ],
            [ 'img_id' => 139, 'label' => __( 'Long Shelf Life',   'solmaram' ) ],
        ];
        foreach ( $values as $v ) :
            $icon_url = wp_get_attachment_image_url( $v['img_id'], 'thumbnail' );
        ?>
          <li class="value-item">
            <?php if ( $icon_url ) : ?>
              <img src="<?php echo esc_url( $icon_url ); ?>"
                   alt="" aria-hidden="true"
                   class="value-item__icon-img" loading="lazy">
            <?php endif; ?>
            <span class="value-item__label"><?php echo esc_html( $v['label'] ); ?></span>
          </li>
        <?php endforeach; ?>
      </ul>
    </div>
  </section>

  <?php /* ── 3. Best sellers ──────────────────────────────────────── */ ?>
  <?php if ( class_exists( 'WooCommerce' ) ) : ?>
  <section class="section section--alt section-bestsellers">
    <div class="container">
      <h2 class="section-title text-center"><?php esc_html_e( 'Best Sellers', 'solmaram' ); ?></h2>
      <p class="section-subtitle text-center"><?php esc_html_e( 'Our most loved products', 'solmaram' ); ?></p>

      <?php
      $best_sellers = wc_get_products( [
          'tag'    => [ 'best-seller' ],
          'limit'  => 4,
          'status' => 'publish',
      ] );
      ?>
      <?php if ( $best_sellers ) : ?>
      <div class="grid-4 products-grid">
        <?php foreach ( $best_sellers as $product ) :
            $GLOBALS['post'] = get_post( $product->get_id() );
            setup_postdata( $GLOBALS['post'] );
        ?>
          <?php wc_get_template_part( 'content', 'product' ); ?>
        <?php endforeach; wp_reset_postdata(); ?>
      </div>
      <?php endif; ?>

      <div class="text-center mt-40">
        <a href="<?php echo esc_url( wc_get_page_permalink( 'shop' ) ); ?>" class="btn btn-outline">
          <?php esc_html_e( 'View All Products', 'solmaram' ); ?>
        </a>
      </div>
    </div>
  </section>
  <?php endif; ?>

  <?php /* ── 4. About freeze-drying teaser ───────────────────────── */ ?>
  <section class="section section-fd-teaser">
    <div class="container fd-teaser__inner">
      <div class="fd-teaser__content">
        <h2 class="section-title"><?php esc_html_e( 'What is Freeze-Drying?', 'solmaram' ); ?></h2>
        <p><?php esc_html_e( 'Freeze-drying removes moisture while keeping up to 97% of nutrients intact, giving our products an incredible shelf life of up to 25 years — with no refrigeration needed.', 'solmaram' ); ?></p>
        <a href="<?php
            $sub_id  = 35; // EN sublimation page — pll_get_post resolves to current language
            $sub_url = function_exists( 'pll_get_post' )
                ? get_permalink( pll_get_post( $sub_id, pll_current_language() ) ?: $sub_id )
                : get_permalink( $sub_id );
            echo esc_url( $sub_url );
        ?>" class="btn btn-outline mt-24">
          <?php esc_html_e( 'Learn More', 'solmaram' ); ?>
        </a>
      </div>
      <div class="fd-teaser__image">
        <?php
        $fd_img_id = get_theme_mod( 'sm_fd_image' );
        if ( $fd_img_id ) echo wp_get_attachment_image( $fd_img_id, 'large', false, [ 'class' => 'fd-teaser__img' ] );
        ?>
      </div>
    </div>
  </section>

  <?php /* ── 5. About us ──────────────────────────────────────────── */ ?>
  <section class="section section--alt section-about">
    <div class="container about__inner">
      <div class="about__image">
        <?php
        $about_img_id = get_theme_mod( 'sm_about_image' );
        if ( $about_img_id ) echo wp_get_attachment_image( $about_img_id, 'large', false, [ 'class' => 'about__img' ] );
        ?>
      </div>
      <div class="about__content">
        <h2 class="section-title"><?php esc_html_e( 'About SolMaram', 'solmaram' ); ?></h2>
        <?php
        $about_text = get_theme_mod( 'sm_about_text', '' );
        if ( $about_text ) {
            echo wp_kses_post( wpautop( $about_text ) );
        }
        ?>
      </div>
    </div>
  </section>

  <?php /* ── 6. Customer reviews carousel ─────────────────────────── */ ?>
  <section class="section section-reviews">
    <div class="container">
      <h2 class="section-title text-center"><?php esc_html_e( 'What Our Customers Say', 'solmaram' ); ?></h2>

      <?php
      $reviews = get_comments( [
          'status'       => 'approve',
          'type'         => 'review',
          'number'       => 12,
          'meta_query'   => [ [ 'key' => 'rating', 'value' => 4, 'compare' => '>=', 'type' => 'NUMERIC' ] ],
          'orderby'      => 'comment_date_gmt',
          'order'        => 'DESC',
      ] );
      ?>
      <?php if ( $reviews ) : ?>
      <div class="reviews-carousel" data-autoplay="true">
        <?php foreach ( $reviews as $review ) :
          $rating = intval( get_comment_meta( $review->comment_ID, 'rating', true ) );
        ?>
          <article class="review-card">
            <div class="review-card__stars" aria-label="<?php printf( esc_attr__( '%d out of 5 stars', 'solmaram' ), $rating ); ?>">
              <?php echo str_repeat( '★', $rating ) . str_repeat( '☆', 5 - $rating ); ?>
            </div>
            <blockquote class="review-card__text">
              <?php echo wp_kses_post( $review->comment_content ); ?>
            </blockquote>
            <footer class="review-card__author">
              <cite><?php echo esc_html( $review->comment_author ); ?></cite>
            </footer>
          </article>
        <?php endforeach; ?>
      </div>
      <?php endif; ?>
    </div>
  </section>

  <?php /* ── 7. Blog & Recipes ────────────────────────────────────── */ ?>
  <section class="section section--alt section-blog">
    <div class="container">
      <h2 class="section-title text-center"><?php esc_html_e( 'Blog & Recipes', 'solmaram' ); ?></h2>

      <?php
      $posts = new WP_Query( [
          'posts_per_page' => 3,
          'post_status'    => 'publish',
          'no_found_rows'  => true,
      ] );
      ?>
      <?php if ( $posts->have_posts() ) : ?>
      <div class="grid-3 blog-grid">
        <?php while ( $posts->have_posts() ) : $posts->the_post(); ?>
          <article class="blog-card">
            <?php if ( has_post_thumbnail() ) : ?>
              <a class="blog-card__image" href="<?php the_permalink(); ?>">
                <?php the_post_thumbnail( 'blog-card', [ 'alt' => esc_attr( get_the_title() ) ] ); ?>
              </a>
            <?php endif; ?>
            <div class="blog-card__body">
              <div class="blog-card__meta text-muted">
                <?php the_category( ' · ' ); ?>
                <span>·</span>
                <time datetime="<?php echo esc_attr( get_the_date( 'c' ) ); ?>"><?php the_date(); ?></time>
              </div>
              <h3 class="blog-card__title">
                <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
              </h3>
              <p class="blog-card__excerpt"><?php the_excerpt(); ?></p>
            </div>
          </article>
        <?php endwhile; wp_reset_postdata(); ?>
      </div>
      <?php endif; ?>

      <div class="text-center mt-40">
        <a href="<?php echo esc_url( get_permalink( get_option( 'page_for_posts' ) ) ); ?>" class="btn btn-outline">
          <?php esc_html_e( 'All Posts', 'solmaram' ); ?>
        </a>
      </div>
    </div>
  </section>

  <?php /* ── 8. Instagram feed ────────────────────────────────────── */ ?>
  <?php
  if ( class_exists( 'SM_Instagram_Feed' ) ) :
      $photos = SM_Instagram_Feed::get_photos( 8 );
  ?>
  <?php if ( ! empty( $photos ) ) : ?>
  <section class="section section-instagram">
    <div class="container">
      <h2 class="section-title text-center">
        <?php esc_html_e( 'Follow us on Instagram', 'solmaram' ); ?>
        <a href="https://instagram.com/solmaram_ukraine" target="_blank" rel="noopener" class="instagram-handle">@solmaram_ukraine</a>
      </h2>
      <div class="instagram-grid">
        <?php foreach ( $photos as $photo ) : ?>
          <a href="<?php echo esc_url( $photo['url'] ); ?>" target="_blank" rel="noopener" class="instagram-item">
            <img src="<?php echo esc_url( $photo['thumbnail'] ); ?>" alt="" loading="lazy">
          </a>
        <?php endforeach; ?>
      </div>
    </div>
  </section>
  <?php endif; ?>
  <?php endif; ?>

</main>

<?php get_footer(); ?>
