<?php
// Fallback template — WordPress requires this file.
get_header();
?>
<main id="main" class="site-main">
  <div class="container" style="padding-block:48px">
    <?php if ( have_posts() ) : while ( have_posts() ) : the_post(); ?>
      <article <?php post_class(); ?>>
        <h2><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h2>
        <?php the_excerpt(); ?>
      </article>
    <?php endwhile; else : ?>
      <p><?php esc_html_e( 'Nothing found.', 'solmaram' ); ?></p>
    <?php endif; ?>
  </div>
</main>
<?php get_footer(); ?>
