<?php
/**
 * Seed the "Freeze-dried Mango Powder" product.
 *
 * A variable product on the pa_vaga (Вага) package-size attribute with two
 * variations, 50 г / 100 г. Ukrainian is the canonical copy (post_title /
 * post_excerpt / post_content); EN + PT live in _sm_{field}_{lang} postmeta and
 * are injected by SM_Product_I18n.
 *
 * Idempotent: identified by the _sm_seed_key = 'mango-powder' marker meta, so
 * re-running updates the same product instead of creating duplicates.
 *
 * Requires the source image at /var/www/html/wp-content/uploads/tmp-mango.jpg
 * (imported into the media library on first run, then reused).
 *
 * Run:  docker compose run --rm wpcli wp eval-file docker/seed-mango-powder.php --allow-root
 */
defined( 'ABSPATH' ) || exit;

$SEED_KEY  = 'mango-powder';
$IMG_TMP   = WP_CONTENT_DIR . '/uploads/tmp-mango.jpg';
$SLUG      = 'freeze-dried-mango-powder';
$CAT_SLUG  = 'freeze-dried-fruits-berries';
$USE_CASES = [ 'snack', 'cooking', 'sweets' ];
$PRICES    = [ '50g' => '699', '100g' => '1399' ]; // UAH
$WEIGHTS   = [ '50g' => '0.05', '100g' => '0.10' ]; // kg (Nova Poshta shipping)

/* ── Copy blocks ───────────────────────────────────────────────────────── */
$ua_name = 'Сублімоване манго, порошок';
$en_name = 'Freeze-dried Mango Powder';
$pt_name = 'Manga Liofilizada em Pó';

$ua_short = 'Сублімоване манго у порошку — 100% натуральне, без цукру та добавок. Ідеальне для смузі, десертів, випічки та натурального фарбування.';
$en_short = 'Freeze-dried mango powder — 100% natural, no sugar or additives. Ideal for smoothies, desserts, baking and natural colouring.';
$pt_short = 'Manga liofilizada em pó — 100% natural, sem açúcar nem aditivos. Ideal para smoothies, sobremesas, pastelaria e coloração natural.';

$ua_desc = <<<HTML
<p><strong>Склад</strong><br>100% сублімоване манго.</p>

<p>Без цукру. Без консервантів. Без ароматизаторів. Без барвників. Без ГМО.</p>

<p>Сублімований порошок з манго стане чудовим інгредієнтом для:</p>
<ul>
<li>смузі та фруктових коктейлів;</li>
<li>йогуртів, каш і граноли;</li>
<li>морозива, сорбетів і молочних десертів;</li>
<li>кремів, мусів, чизкейків і тортів;</li>
<li>шоколаду, цукерок і фруктових начинок;</li>
<li>випічки та кондитерських виробів;</li>
<li>лимонадів, чаїв і функціональних напоїв;</li>
<li>натурального фарбування кремів і десертів.</li>
</ul>

<h3>Вітамінна та мінеральна цінність</h3>
<p>Манго є природним джерелом вітамінів, мінералів і біологічно активних рослинних сполук.</p>

<p>До його складу входять:</p>
<ul>
<li><strong>Вітамін A (у формі бета-каротину)</strong> — сприяє підтриманню нормального зору, здоров’я шкіри та нормальної роботи імунної системи.</li>
<li><strong>Вітамін C</strong> — підтримує імунну систему, бере участь у синтезі колагену та допомагає захищати клітини від окисного стресу.</li>
<li><strong>Вітамін E</strong> — сприяє захисту клітин від дії вільних радикалів.</li>
<li><strong>Вітаміни групи B (B1, B2, B6, B9)</strong> — беруть участь в енергетичному обміні та підтримують нормальне функціонування нервової системи.</li>
<li><strong>Калій</strong> — необхідний для нормальної роботи серця, м’язів і підтримання нормального артеріального тиску.</li>
<li><strong>Магній</strong> — бере участь у роботі нервової системи, м’язів та енергетичному обміні.</li>
<li><strong>Мідь</strong> — сприяє нормальному транспорту заліза в організмі та підтримці імунної системи.</li>
</ul>

<p>Манго також містить природні каротиноїди, поліфеноли, харчові волокна та інші антиоксидантні сполуки, які є важливою складовою збалансованого харчування.</p>

<h3>Умови зберігання</h3>
<p>Зберігати в сухому, прохолодному місці при температурі до +25 °C у щільно закритій упаковці, захищаючи від вологи та прямих сонячних променів.</p>

<h3>Термін придатності</h3>
<p>24 місяці за умови дотримання умов зберігання.</p>
HTML;

$en_desc = <<<HTML
<p><strong>Ingredients</strong><br>100% freeze-dried mango.</p>

<p>No sugar. No preservatives. No flavourings. No colourings. No GMOs.</p>

<p>Freeze-dried mango powder makes a great ingredient for:</p>
<ul>
<li>smoothies and fruit shakes;</li>
<li>yogurts, porridges and granola;</li>
<li>ice cream, sorbets and dairy desserts;</li>
<li>creams, mousses, cheesecakes and cakes;</li>
<li>chocolate, sweets and fruit fillings;</li>
<li>baking and confectionery;</li>
<li>lemonades, teas and functional drinks;</li>
<li>natural colouring of creams and desserts.</li>
</ul>

<h3>Vitamin and mineral value</h3>
<p>Mango is a natural source of vitamins, minerals and biologically active plant compounds.</p>

<p>It contains:</p>
<ul>
<li><strong>Vitamin A (as beta-carotene)</strong> — helps maintain normal vision, skin health and normal immune function.</li>
<li><strong>Vitamin C</strong> — supports the immune system, contributes to collagen synthesis and helps protect cells from oxidative stress.</li>
<li><strong>Vitamin E</strong> — helps protect cells from the effects of free radicals.</li>
<li><strong>B vitamins (B1, B2, B6, B9)</strong> — take part in energy metabolism and support normal nervous system function.</li>
<li><strong>Potassium</strong> — needed for normal heart and muscle function and for maintaining normal blood pressure.</li>
<li><strong>Magnesium</strong> — takes part in nervous system and muscle function and in energy metabolism.</li>
<li><strong>Copper</strong> — contributes to normal iron transport in the body and supports the immune system.</li>
</ul>

<p>Mango also contains natural carotenoids, polyphenols, dietary fibre and other antioxidant compounds, which are an important part of a balanced diet.</p>

<h3>Storage conditions</h3>
<p>Store in a dry, cool place at a temperature up to +25 °C, in tightly closed packaging, protected from moisture and direct sunlight.</p>

<h3>Shelf life</h3>
<p>24 months when storage conditions are observed.</p>
HTML;

$pt_desc = <<<HTML
<p><strong>Ingredientes</strong><br>100% manga liofilizada.</p>

<p>Sem açúcar. Sem conservantes. Sem aromatizantes. Sem corantes. Sem OGM.</p>

<p>O pó de manga liofilizada é um excelente ingrediente para:</p>
<ul>
<li>smoothies e batidos de fruta;</li>
<li>iogurtes, papas e granola;</li>
<li>gelados, sorvetes e sobremesas lácteas;</li>
<li>cremes, mousses, cheesecakes e bolos;</li>
<li>chocolate, rebuçados e recheios de fruta;</li>
<li>pastelaria e produtos de confeitaria;</li>
<li>limonadas, chás e bebidas funcionais;</li>
<li>coloração natural de cremes e sobremesas.</li>
</ul>

<h3>Valor vitamínico e mineral</h3>
<p>A manga é uma fonte natural de vitaminas, minerais e compostos vegetais biologicamente ativos.</p>

<p>É composta por:</p>
<ul>
<li><strong>Vitamina A (na forma de betacaroteno)</strong> — contribui para a manutenção da visão normal, da saúde da pele e do funcionamento normal do sistema imunitário.</li>
<li><strong>Vitamina C</strong> — apoia o sistema imunitário, participa na síntese de colagénio e ajuda a proteger as células do stress oxidativo.</li>
<li><strong>Vitamina E</strong> — ajuda a proteger as células da ação dos radicais livres.</li>
<li><strong>Vitaminas do complexo B (B1, B2, B6, B9)</strong> — participam no metabolismo energético e apoiam o funcionamento normal do sistema nervoso.</li>
<li><strong>Potássio</strong> — necessário para o funcionamento normal do coração e dos músculos e para a manutenção de uma tensão arterial normal.</li>
<li><strong>Magnésio</strong> — participa no funcionamento do sistema nervoso e dos músculos e no metabolismo energético.</li>
<li><strong>Cobre</strong> — contribui para o transporte normal de ferro no organismo e apoia o sistema imunitário.</li>
</ul>

<p>A manga também contém carotenoides naturais, polifenóis, fibra alimentar e outros compostos antioxidantes, que são uma parte importante de uma alimentação equilibrada.</p>

<h3>Condições de armazenamento</h3>
<p>Conservar em local seco e fresco, a uma temperatura até +25 °C, em embalagem bem fechada, protegido da humidade e da luz solar direta.</p>

<h3>Prazo de validade</h3>
<p>24 meses, desde que sejam respeitadas as condições de armazenamento.</p>
HTML;

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
	$dest = trailingslashit( $up['path'] ) . 'freeze-dried-mango-powder.jpg';
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
$tax     = 'pa_vaga';
$att_id  = wc_attribute_taxonomy_id_by_name( 'vaga' );
$slugs   = array_keys( $PRICES ); // 50g, 100g
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

$var = new WC_Product_Variable( $pid );
$var->set_attributes( [ $tax => $attribute ] );
$var->save();

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
