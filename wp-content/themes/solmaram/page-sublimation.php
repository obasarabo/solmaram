<?php
/**
 * Template Name: Sublimation (Freeze-Drying Education)
 * Template Post Type: page
 *
 * Educational page at /sublimation/ (FR-06)
 */
get_header();
?>

<main id="main" class="site-main sublimation-page">
  <div class="container" style="padding-block:64px; max-width:900px">

    <h1 class="section-title"><?php esc_html_e( 'What is Freeze-Drying (Sublimation)?', 'solmaram' ); ?></h1>
    <p class="section-subtitle"><?php esc_html_e( "How we preserve 100% of nature's goodness", 'solmaram' ); ?></p>

    <?php the_content(); ?>

    <?php /* ── Comparison table ──────────────────────────────────── */ ?>
    <section class="sublimation-comparison" aria-label="<?php esc_attr_e( 'Drying method comparison', 'solmaram' ); ?>">
      <h2><?php esc_html_e( 'Freeze-Drying vs Other Methods', 'solmaram' ); ?></h2>
      <div style="overflow-x:auto; margin-top:20px">
        <table>
          <thead>
            <tr>
              <th><?php esc_html_e( 'Criteria', 'solmaram' ); ?></th>
              <th><?php esc_html_e( 'Freeze-Drying', 'solmaram' ); ?></th>
              <th><?php esc_html_e( 'Freezing', 'solmaram' ); ?></th>
              <th><?php esc_html_e( 'Conventional Drying', 'solmaram' ); ?></th>
            </tr>
          </thead>
          <tbody>
            <tr>
              <td><?php esc_html_e( 'Nutrients retained', 'solmaram' ); ?></td>
              <td><?php esc_html_e( 'Up to 97%', 'solmaram' ); ?></td>
              <td><?php esc_html_e( '~60–80%', 'solmaram' ); ?></td>
              <td><?php esc_html_e( '~20–40%', 'solmaram' ); ?></td>
            </tr>
            <tr>
              <td><?php esc_html_e( 'Shelf life', 'solmaram' ); ?></td>
              <td><?php esc_html_e( 'Up to 25 years', 'solmaram' ); ?></td>
              <td><?php esc_html_e( 'Up to 1 year', 'solmaram' ); ?></td>
              <td><?php esc_html_e( '1–3 years', 'solmaram' ); ?></td>
            </tr>
            <tr>
              <td><?php esc_html_e( 'Requires refrigeration', 'solmaram' ); ?></td>
              <td><?php esc_html_e( 'No', 'solmaram' ); ?></td>
              <td><?php esc_html_e( 'Yes', 'solmaram' ); ?></td>
              <td><?php esc_html_e( 'No', 'solmaram' ); ?></td>
            </tr>
            <tr>
              <td><?php esc_html_e( 'Original texture', 'solmaram' ); ?></td>
              <td><?php esc_html_e( 'Preserved', 'solmaram' ); ?></td>
              <td><?php esc_html_e( 'Changes', 'solmaram' ); ?></td>
              <td><?php esc_html_e( 'Significantly changes', 'solmaram' ); ?></td>
            </tr>
            <tr>
              <td><?php esc_html_e( 'Weight', 'solmaram' ); ?></td>
              <td><?php esc_html_e( 'Very light', 'solmaram' ); ?></td>
              <td><?php esc_html_e( 'Heavy', 'solmaram' ); ?></td>
              <td><?php esc_html_e( 'Light', 'solmaram' ); ?></td>
            </tr>
          </tbody>
        </table>
      </div>
    </section>

    <?php /* ── How we make it ──────────────────────────────────────── */ ?>
    <section style="margin-top:60px">
      <h2><?php esc_html_e( 'How We Make It', 'solmaram' ); ?></h2>
      <p><?php esc_html_e( 'Our freeze-drying process is carried out in a controlled, clean environment — from harvest to packaging — preserving every nutrient and flavour molecule.', 'solmaram' ); ?></p>
      <?php
      // Admin can embed production photos/video via the page content editor
      ?>
    </section>

    <?php /* ── Quality certificates ─────────────────────────────────── */ ?>
    <?php
    $cert_ids = get_post_meta( get_the_ID(), '_sm_certificates', true );
    if ( $cert_ids ) :
        $certs = explode( ',', $cert_ids );
    ?>
    <section style="margin-top:60px">
      <h2><?php esc_html_e( 'Quality Certificates', 'solmaram' ); ?></h2>
      <div class="grid-4" style="margin-top:20px">
        <?php foreach ( $certs as $cert_id ) :
            $cert_id = absint( trim( $cert_id ) );
            $cert_url = wp_get_attachment_url( $cert_id );
            $cert_img = wp_get_attachment_image( $cert_id, 'medium' );
            if ( ! $cert_url ) continue;
        ?>
          <a href="<?php echo esc_url( $cert_url ); ?>" target="_blank" rel="noopener" class="cert-item">
            <?php echo $cert_img; // phpcs:ignore ?>
          </a>
        <?php endforeach; ?>
      </div>
    </section>
    <?php endif; ?>

    <?php /* ── Internal links to product categories ──────────────────── */ ?>
    <section style="margin-top:60px">
      <h2><?php esc_html_e( 'Explore Our Products', 'solmaram' ); ?></h2>
      <div class="grid-2" style="margin-top:20px; gap:16px">
        <?php
        $cats = get_terms( [ 'taxonomy' => 'product_cat', 'parent' => 0, 'hide_empty' => true ] );
        if ( is_wp_error( $cats ) ) $cats = [];
        foreach ( $cats as $cat ) :
            if ( $cat->slug === 'uncategorized' ) continue;
        ?>
          <a href="<?php echo esc_url( get_term_link( $cat ) ); ?>" class="btn btn-outline">
            <?php echo esc_html( $cat->name ); ?>
          </a>
        <?php endforeach; ?>
      </div>
    </section>

  </div>
</main>

<?php get_footer(); ?>
