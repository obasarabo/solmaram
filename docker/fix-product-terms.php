<?php
// Create proper product_cat terms
$veg_term   = wp_insert_term( 'Freeze-dried Vegetables',      'product_cat', [ 'slug' => 'freeze-dried-vegetables' ] );
$fruit_term = wp_insert_term( 'Freeze-dried Fruits & Berries', 'product_cat', [ 'slug' => 'freeze-dried-fruits-berries' ] );

$veg_id   = is_wp_error( $veg_term )   ? $veg_term->error_data['term_exists']   : $veg_term['term_id'];
$fruit_id = is_wp_error( $fruit_term ) ? $fruit_term->error_data['term_exists'] : $fruit_term['term_id'];

// Create proper product_tag for best-seller
$tag_term   = wp_insert_term( 'Best Seller', 'product_tag', [ 'slug' => 'best-seller' ] );
$tag_id     = is_wp_error( $tag_term ) ? $tag_term->error_data['term_exists'] : $tag_term['term_id'];

WP_CLI::log( "product_cat — Vegetables: $veg_id, Fruits: $fruit_id | product_tag — best-seller: $tag_id" );

$assignments = [
    'Freeze-dried Peas'          => [ 'cat' => $veg_id,   'best' => true ],
    'Freeze-dried Sweet Corn'    => [ 'cat' => $veg_id,   'best' => true ],
    'Freeze-dried Broccoli'      => [ 'cat' => $veg_id,   'best' => false ],
    'Freeze-dried Carrots'       => [ 'cat' => $veg_id,   'best' => false ],
    'Freeze-dried Spinach'       => [ 'cat' => $veg_id,   'best' => false ],
    'Freeze-dried Strawberries'  => [ 'cat' => $fruit_id, 'best' => true ],
    'Freeze-dried Blueberries'   => [ 'cat' => $fruit_id, 'best' => true ],
    'Freeze-dried Raspberries'   => [ 'cat' => $fruit_id, 'best' => false ],
    'Freeze-dried Apple Slices'  => [ 'cat' => $fruit_id, 'best' => false ],
    'Freeze-dried Banana Chips'  => [ 'cat' => $fruit_id, 'best' => false ],
];

foreach ( $assignments as $title => $data ) {
    $posts = get_posts( [ 'post_type' => 'product', 'title' => $title, 'posts_per_page' => 1 ] );
    if ( empty( $posts ) ) {
        WP_CLI::warning( "Not found: $title" );
        continue;
    }
    $id = $posts[0]->ID;
    wp_set_post_terms( $id, [ (int) $data['cat'] ], 'product_cat' );
    if ( $data['best'] ) {
        wp_set_post_terms( $id, [ (int) $tag_id ], 'product_tag' );
    }
    wc_delete_product_transients( $id );
    WP_CLI::success( "Fixed: $title (ID $id)" );
}
