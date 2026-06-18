<?php
/**
 * Seed: give every product a "Вага" (package size) attribute with
 * 20 г / 50 г / 100 г variations.
 *
 * Draft pricing:  20g = current regular price,  50g = round(2.5×),  100g = round(5×).
 * Idempotent: re-running deletes existing variations and rebuilds cleanly.
 *
 * Run:  wp eval-file docker/seed-product-variations.php
 */
defined( 'ABSPATH' ) || exit;

$product_ids = [ 25, 26, 27, 28, 29, 30, 31, 32, 33, 34 ];

/* ── 1. Global attribute "Вага" (taxonomy pa_vaga), numeric-sorted ─────── */
$att_id = wc_attribute_taxonomy_id_by_name( 'vaga' );
$att_args = [
    'name' => 'Вага', 'slug' => 'vaga', 'type' => 'select',
    'order_by' => 'name_num', 'has_archives' => false,
];
if ( ! $att_id ) {
    $att_id = wc_create_attribute( $att_args );
    if ( is_wp_error( $att_id ) ) { echo 'attribute error: ' . $att_id->get_error_message() . "\n"; return; }
} else {
    wc_update_attribute( $att_id, $att_args ); // ensure numeric ordering (20 < 50 < 100)
}
$tax = 'pa_vaga';
if ( ! taxonomy_exists( $tax ) ) {
    register_taxonomy( $tax, [ 'product' ], [ 'hierarchical' => false, 'show_ui' => false, 'query_var' => true, 'rewrite' => false ] );
}

/* ── 2. Terms 20 г / 50 г / 100 г ──────────────────────────────────────── */
$sizes    = [ '20g' => '20 г', '50g' => '50 г', '100g' => '100 г' ];
$term_ids = [];
foreach ( $sizes as $slug => $name ) {
    $existing = get_term_by( 'slug', $slug, $tax );
    if ( $existing ) {
        $term_ids[ $slug ] = (int) $existing->term_id;
    } else {
        $res = wp_insert_term( $name, $tax, [ 'slug' => $slug ] );
        $term_ids[ $slug ] = is_wp_error( $res ) ? 0 : (int) $res['term_id'];
    }
}
echo "attribute pa_vaga id=$att_id terms=" . implode( ',', $term_ids ) . "\n";

/* ── 3. Convert each product to variable + 3 priced variations ─────────── */
$mult = [ '20g' => 1.0, '50g' => 2.5, '100g' => 5.0 ];

foreach ( $product_ids as $pid ) {
    $p = wc_get_product( $pid );
    if ( ! $p ) { echo "#$pid : NOT FOUND\n"; continue; }

    $name = $p->get_name();
    $base = (float) $p->get_regular_price();
    if ( $base <= 0 ) { $base = (float) $p->get_price(); }
    if ( $base <= 0 ) { echo "#$pid : no base price, skipped\n"; continue; }

    // Delete any existing variations (including orphans from a previous run).
    $existing = get_posts( [ 'post_type' => 'product_variation', 'post_parent' => $pid, 'numberposts' => -1, 'fields' => 'ids', 'post_status' => 'any' ] );
    foreach ( $existing as $vid ) { wp_delete_post( $vid, true ); }

    // Switch type to variable and assign the size terms.
    wp_set_object_terms( $pid, 'variable', 'product_type' );
    wp_set_object_terms( $pid, array_keys( $sizes ), $tax );

    $attribute = new WC_Product_Attribute();
    $attribute->set_id( $att_id );
    $attribute->set_name( $tax );
    $attribute->set_options( array_values( $term_ids ) );
    $attribute->set_position( 0 );
    $attribute->set_visible( true );
    $attribute->set_variation( true );

    // Force a variable-typed instance (avoids reusing the cached simple object).
    $var = new WC_Product_Variable( $pid );
    $var->set_attributes( [ $tax => $attribute ] );
    $var->save();

    // Create the three variations.
    $line = [];
    foreach ( $mult as $slug => $m ) {
        $price = (string) (int) round( $base * $m );
        $v = new WC_Product_Variation();
        $v->set_parent_id( $pid );
        $v->set_attributes( [ $tax => $slug ] );
        $v->set_regular_price( $price );
        $v->set_manage_stock( false );
        $v->set_stock_status( 'instock' );
        $v->set_status( 'publish' );
        $v->save();
        $line[] = "$slug=$price";
    }

    WC_Product_Variable::sync( $pid );
    wc_delete_product_transients( $pid );
    clean_post_cache( $pid );

    echo "#$pid $name (" . implode( ' ', $line ) . ")\n";
}

echo "DONE\n";
