<?php
/**
 * Shared builder for the freeze-dried *powder* products.
 *
 * The caller (e.g. seed-blueberry-powder.php) defines these variables, then
 * `require`s this file:
 *   $SEED_KEY, $IMG_TMP, $SLUG, $CAT_SLUG, $USE_CASES, $PRICES, $WEIGHTS,
 *   $ua_name/$en_name/$pt_name, $ua_short/$en_short/$pt_short,
 *   $ua_desc/$en_desc/$pt_desc.
 *
 * Creates a 2-weight variable product on the pa_vaga attribute with the
 * Ukrainian copy canonical and EN/PT in _sm_{field}_{lang} postmeta. Idempotent
 * via the _sm_seed_key marker.
 */
defined( 'ABSPATH' ) || exit;

/* ── 1. Locate or create the product post ──────────────────────────────── */
$existing = get_posts( [
	'post_type'   => 'product',
	'post_status' => 'any',
	'numberposts' => 1,
	'fields'      => 'ids',
	'meta_key'    => '_sm_seed_key',
	'meta_value'  => $SEED_KEY,
] );

$post_arr = [
	'post_title'   => $ua_name,
	'post_name'    => $SLUG, // English slug, like the other products
	'post_excerpt' => $ua_short,
	'post_content' => $ua_desc,
	'post_status'  => 'publish',
	'post_type'    => 'product',
];

if ( $existing ) {
	$pid = (int) $existing[0];
	$post_arr['ID'] = $pid;
	wp_update_post( $post_arr );
	echo "Updating existing product #$pid\n";
} else {
	$pid = (int) wp_insert_post( $post_arr );
	update_post_meta( $pid, '_sm_seed_key', $SEED_KEY );
	echo "Created product #$pid\n";
}

/* ── 2. Category + use cases + EN/PT translation meta ───────────────────── */
$cat = get_term_by( 'slug', $CAT_SLUG, 'product_cat' );
if ( $cat ) {
	wp_set_object_terms( $pid, [ (int) $cat->term_id ], 'product_cat' );
}
wp_set_object_terms( $pid, $USE_CASES, 'sm_use_case', false );

update_post_meta( $pid, '_sm_name_en',              $en_name );
update_post_meta( $pid, '_sm_short_description_en', $en_short );
update_post_meta( $pid, '_sm_description_en',       $en_desc );
update_post_meta( $pid, '_sm_name_pt',              $pt_name );
update_post_meta( $pid, '_sm_short_description_pt', $pt_short );
update_post_meta( $pid, '_sm_description_pt',       $pt_desc );

update_post_meta( $pid, '_visibility',   'visible' );
update_post_meta( $pid, '_virtual',      'no' );
update_post_meta( $pid, '_manage_stock', 'no' );
update_post_meta( $pid, '_stock_status', 'instock' );

/* ── 3. Featured image (import once) ───────────────────────────────────── */
$thumb_id = (int) get_post_thumbnail_id( $pid );
if ( ! $thumb_id && file_exists( $IMG_TMP ) ) {
	require_once ABSPATH . 'wp-admin/includes/image.php';
	$up   = wp_upload_dir();
	$dest = trailingslashit( $up['path'] ) . $SLUG . '.jpg';
	copy( $IMG_TMP, $dest );

	$att_id = wp_insert_attachment( [
		'post_mime_type' => 'image/jpeg',
		'post_title'     => $en_name,
		'post_status'    => 'inherit',
	], $dest, $pid );
	$meta = wp_generate_attachment_metadata( $att_id, $dest );
	wp_update_attachment_metadata( $att_id, $meta );
	update_post_meta( $att_id, '_wp_attachment_image_alt', $en_name );
	set_post_thumbnail( $pid, $att_id );
	echo "Imported featured image (attachment #$att_id)\n";
} else {
	echo "Featured image already set (attachment #$thumb_id) — skipped import\n";
}

/* ── 4. Variable product: pa_vaga 50 г / 100 г ─────────────────────────── */
$tax      = 'pa_vaga';
$att_id   = wc_attribute_taxonomy_id_by_name( 'vaga' );
$slugs    = array_keys( $PRICES ); // 50g, 100g
$term_ids = [];
foreach ( $slugs as $slug ) {
	$t = get_term_by( 'slug', $slug, $tax );
	if ( $t ) { $term_ids[] = (int) $t->term_id; }
}

// Remove any existing variations so re-runs rebuild cleanly.
$old = get_posts( [ 'post_type' => 'product_variation', 'post_parent' => $pid, 'numberposts' => -1, 'fields' => 'ids', 'post_status' => 'any' ] );
foreach ( $old as $vid ) { wp_delete_post( $vid, true ); }

wp_set_object_terms( $pid, 'variable', 'product_type' );
wp_set_object_terms( $pid, $slugs, $tax );

$attribute = new WC_Product_Attribute();
$attribute->set_id( $att_id );
$attribute->set_name( $tax );
$attribute->set_options( $term_ids );
$attribute->set_position( 0 );
$attribute->set_visible( true );
$attribute->set_variation( true );

$variable = new WC_Product_Variable( $pid );
$variable->set_attributes( [ $tax => $attribute ] );
$variable->save();

$line = [];
foreach ( $PRICES as $slug => $price ) {
	$v = new WC_Product_Variation();
	$v->set_parent_id( $pid );
	$v->set_attributes( [ $tax => $slug ] );
	$v->set_regular_price( $price );
	$v->set_weight( $WEIGHTS[ $slug ] );
	$v->set_manage_stock( false );
	$v->set_stock_status( 'instock' );
	$v->set_status( 'publish' );
	$v->save();
	$line[] = "$slug=$price";
}

WC_Product_Variable::sync( $pid );
wc_delete_product_transients( $pid );
clean_post_cache( $pid );
wp_cache_flush();

echo "Product #$pid \"$ua_name\" ready (" . implode( ' ', $line ) . ")\n";
echo "URL: " . get_permalink( $pid ) . "\n";
echo "DONE\n";
