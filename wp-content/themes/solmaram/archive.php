<?php
/**
 * Blog index with category filter (FR-08)
 */
get_header();
?>

<main id="main" class="site-main blog-index">
  <div class="container">

    <div class="blog-index__header">
      <h1 class="section-title">
        <?php
        if ( is_category() ) {
            single_cat_title();
        } elseif ( is_tag() ) {
            single_tag_title( __( 'Tag: ', 'solmaram' ) );
        } else {
            esc_html_e( 'Blog & Recipes', 'solmaram' );
        }
        ?>
      </h1>

      <!-- Category filter tabs -->
      <?php
      $blog_cats = get_categories( [ 'hide_empty' => true ] );
      ?>
      <?php if ( $blog_cats ) : ?>
      <nav class="blog-cats" aria-label="<?php esc_attr_e( 'Blog categories', 'solmaram' ); ?>" style="margin-top:24px">
        <ul style="list-style:none; display:flex; gap:8px; flex-wrap:wrap">
          <li>
            <a href="<?php echo esc_url( get_permalink( get_option( 'page_for_posts' ) ) ); ?>"
               class="btn btn-outline <?php echo ! is_category() ? 'btn-primary' : ''; ?>" style="padding:8px 16px; font-size:.85rem">
              <?php esc_html_e( 'All', 'solmaram' ); ?>
            </a>
          </li>
          <?php foreach ( $blog_cats as $cat ) : ?>
            <li>
              <a href="<?php echo esc_url( get_category_link( $cat->term_id ) ); ?>"
                 class="btn btn-outline <?php echo ( is_category( $cat->term_id ) ? 'btn-primary' : '' ); ?>" style="padding:8px 16px; font-size:.85rem">
                <?php echo esc_html( $cat->name ); ?>
              </a>
            </li>
          <?php endforeach; ?>
        </ul>
      </nav>
      <?php endif; ?>

      <!-- Search within blog -->
      <form class="blog-search" method="get" action="<?php echo esc_url( home_url( '/' ) ); ?>" style="margin-top:20px; display:flex; gap:8px; max-width:480px">
        <input type="search" name="s" class="search-field" placeholder="<?php esc_attr_e( 'Search posts…', 'solmaram' ); ?>"
               value="<?php echo esc_attr( get_search_query() ); ?>">
        <input type="hidden" name="post_type" value="post">
        <button type="submit" class="btn btn-primary" style="padding:10px 20px"><?php esc_html_e( 'Search', 'solmaram' ); ?></button>
      </form>
    </div>

    <?php if ( have_posts() ) : ?>
    <div class="grid-3 blog-grid">
      <?php while ( have_posts() ) : the_post(); ?>
        <article <?php post_class( 'blog-card' ); ?>>
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
            <h2 class="blog-card__title">
              <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
            </h2>
            <p class="blog-card__excerpt"><?php the_excerpt(); ?></p>
          </div>
        </article>
      <?php endwhile; ?>
    </div>

    <div style="display:flex; justify-content:center; margin-top:40px">
      <?php solmaram_pagination(); ?>
    </div>

    <?php else : ?>
      <p><?php esc_html_e( 'No posts found.', 'solmaram' ); ?></p>
    <?php endif; ?>

  </div>
</main>

<?php get_footer(); ?>
