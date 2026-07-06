<?php
/**
 * Seed the "Freeze-dried Strawberry Powder" product.
 *
 * A variable product on the pa_vaga (Вага) package-size attribute with two
 * variations, 50 г / 100 г. Ukrainian is the canonical copy (post_title /
 * post_excerpt / post_content); EN + PT live in _sm_{field}_{lang} postmeta and
 * are injected by SM_Product_I18n.
 *
 * Idempotent: identified by the _sm_seed_key = 'strawberry-powder' marker meta,
 * so re-running updates the same product instead of creating duplicates.
 *
 * Requires the source image at /var/www/html/wp-content/uploads/tmp-strawberry.jpg
 * (imported into the media library on first run, then reused).
 *
 * Run:  docker compose run --rm wpcli wp eval-file docker/seed-strawberry-powder.php --allow-root
 */
defined( 'ABSPATH' ) || exit;

$SEED_KEY  = 'strawberry-powder';
$IMG_TMP   = WP_CONTENT_DIR . '/uploads/tmp-strawberry.jpg';
$SLUG      = 'freeze-dried-strawberry-powder';
$CAT_SLUG  = 'freeze-dried-fruits-berries';
$USE_CASES = [ 'snack', 'cooking', 'sweets' ];
$PRICES    = [ '50g' => '699', '100g' => '1399' ]; // UAH
$WEIGHTS   = [ '50g' => '0.05', '100g' => '0.10' ]; // kg (Nova Poshta shipping)

/* ── Copy blocks ───────────────────────────────────────────────────────── */
$ua_name = 'Сублімована полуниця, порошок';
$en_name = 'Freeze-dried Strawberry Powder';
$pt_name = 'Morango Liofilizado em Pó';

$ua_short = 'Сублімована полуниця у порошку — 100% натуральна, без цукру та добавок. Ідеальна для смузі, десертів, випічки та натурального фарбування.';
$en_short = 'Freeze-dried strawberry powder — 100% natural, no sugar or additives. Ideal for smoothies, desserts, baking and natural colouring.';
$pt_short = 'Morango liofilizado em pó — 100% natural, sem açúcar nem aditivos. Ideal para smoothies, sobremesas, pastelaria e coloração natural.';

$ua_desc = <<<HTML
<p><strong>Склад</strong><br>100% сублімована полуниця.</p>

<p>Сублімований порошок з полуниці чудово підходить для:</p>
<ul>
<li>смузі та молочних коктейлів;</li>
<li>йогуртів і каш;</li>
<li>чаю, лимонадів та інших напоїв;</li>
<li>кремів, мусів, морозива;</li>
<li>випічки, десертів і начинок;</li>
<li>цукерок та шоколаду;</li>
<li>натурального фарбування кремів, глазурі та тіста.</li>
</ul>

<h3>Вітамінна та мінеральна цінність</h3>
<p>Полуниця є природним джерелом вітамінів, мінералів та антиоксидантів. Завдяки сублімаційному сушінню більшість цінних компонентів добре зберігається.</p>

<p>Полуниця містить:</p>
<ul>
<li><strong>Вітамін C</strong> — підтримує нормальне функціонування імунної системи, бере участь у синтезі колагену та захищає клітини від окисного стресу.</li>
<li><strong>Фолієву кислоту (вітамін B9)</strong> — сприяє нормальному кровотворенню та процесам поділу клітин.</li>
<li><strong>Вітаміни B1, B2, B6</strong> — беруть участь в енергетичному обміні та підтримують нормальну роботу нервової системи.</li>
<li><strong>Вітамін K</strong> — сприяє нормальному згортанню крові та підтримці здоров’я кісток.</li>
<li><strong>Калій</strong> — необхідний для нормальної роботи м’язів, серця та нервової системи.</li>
<li><strong>Магній</strong> — бере участь у роботі нервової системи та енергетичному обміні.</li>
<li><strong>Марганець</strong> — допомагає захищати клітини від окисного стресу та підтримує здоров’я сполучної тканини.</li>
</ul>

<p>Також полуниця містить природні антиоксиданти — антоціани, елагову кислоту та флавоноїди, які надають ягодам характерного кольору та є природними рослинними сполуками.</p>

<h3>Умови зберігання</h3>
<p>Зберігати в сухому, прохолодному місці при температурі до +25 °C, у щільно закритій упаковці, захищаючи від вологи та прямих сонячних променів.</p>

<h3>Термін придатності</h3>
<p>24 місяці за умови дотримання умов зберігання.</p>
HTML;

$en_desc = <<<HTML
<p><strong>Ingredients</strong><br>100% freeze-dried strawberry.</p>

<p>Freeze-dried strawberry powder is a great fit for:</p>
<ul>
<li>smoothies and milkshakes;</li>
<li>yogurts and porridges;</li>
<li>tea, lemonades and other drinks;</li>
<li>creams, mousses, ice cream;</li>
<li>baking, desserts and fillings;</li>
<li>sweets and chocolate;</li>
<li>natural colouring of creams, glazes and dough.</li>
</ul>

<h3>Vitamin and mineral value</h3>
<p>Strawberry is a natural source of vitamins, minerals and antioxidants. Thanks to freeze-drying, most of the valuable components are well preserved.</p>

<p>Strawberry contains:</p>
<ul>
<li><strong>Vitamin C</strong> — supports normal immune function, contributes to collagen synthesis and protects cells from oxidative stress.</li>
<li><strong>Folic acid (vitamin B9)</strong> — supports normal blood formation and cell division processes.</li>
<li><strong>Vitamins B1, B2, B6</strong> — take part in energy metabolism and support normal nervous system function.</li>
<li><strong>Vitamin K</strong> — contributes to normal blood clotting and the maintenance of healthy bones.</li>
<li><strong>Potassium</strong> — needed for normal muscle, heart and nervous system function.</li>
<li><strong>Magnesium</strong> — takes part in nervous system function and energy metabolism.</li>
<li><strong>Manganese</strong> — helps protect cells from oxidative stress and supports healthy connective tissue.</li>
</ul>

<p>Strawberry also contains natural antioxidants — anthocyanins, ellagic acid and flavonoids — which give the berries their characteristic colour and are naturally occurring plant compounds.</p>

<h3>Storage conditions</h3>
<p>Store in a dry, cool place at a temperature up to +25 °C, in tightly closed packaging, protected from moisture and direct sunlight.</p>

<h3>Shelf life</h3>
<p>24 months when storage conditions are observed.</p>
HTML;

$pt_desc = <<<HTML
<p><strong>Ingredientes</strong><br>100% morango liofilizado.</p>

<p>O pó de morango liofilizado é perfeito para:</p>
<ul>
<li>smoothies e batidos de leite;</li>
<li>iogurtes e papas;</li>
<li>chá, limonadas e outras bebidas;</li>
<li>cremes, mousses, gelados;</li>
<li>pastelaria, sobremesas e recheios;</li>
<li>rebuçados e chocolate;</li>
<li>coloração natural de cremes, coberturas e massas.</li>
</ul>

<h3>Valor vitamínico e mineral</h3>
<p>O morango é uma fonte natural de vitaminas, minerais e antioxidantes. Graças à liofilização, a maioria dos componentes valiosos é bem preservada.</p>

<p>O morango contém:</p>
<ul>
<li><strong>Vitamina C</strong> — apoia o funcionamento normal do sistema imunitário, participa na síntese de colagénio e protege as células do stress oxidativo.</li>
<li><strong>Ácido fólico (vitamina B9)</strong> — contribui para a formação normal do sangue e os processos de divisão celular.</li>
<li><strong>Vitaminas B1, B2, B6</strong> — participam no metabolismo energético e apoiam o funcionamento normal do sistema nervoso.</li>
<li><strong>Vitamina K</strong> — contribui para a coagulação normal do sangue e a manutenção de ossos saudáveis.</li>
<li><strong>Potássio</strong> — necessário para o funcionamento normal dos músculos, do coração e do sistema nervoso.</li>
<li><strong>Magnésio</strong> — participa no funcionamento do sistema nervoso e no metabolismo energético.</li>
<li><strong>Manganês</strong> — ajuda a proteger as células do stress oxidativo e apoia a saúde do tecido conjuntivo.</li>
</ul>

<p>O morango também contém antioxidantes naturais — antocianinas, ácido elágico e flavonoides — que conferem às bagas a sua cor característica e são compostos vegetais de ocorrência natural.</p>

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
	$dest = trailingslashit( $up['path'] ) . 'freeze-dried-strawberry-powder.jpg';
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
