<?php
/**
 * Template for all standard pages (cart, checkout, my-account, etc.)
 * WooCommerce block content lives in post_content and requires the_content().
 */
get_header();
?>
<main id="main" class="site-main">
  <div class="container" style="padding-block:48px">
    <?php while ( have_posts() ) : the_post(); ?>
      <?php the_content(); ?>
    <?php endwhile; ?>
  </div>
</main>
<?php get_footer(); ?>
