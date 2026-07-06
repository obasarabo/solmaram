<?php
/**
 * Seed the "Freeze-dried Beetroot Powder" product.
 *
 * A variable product on the pa_vaga (Вага) package-size attribute with two
 * variations, 50 г / 100 г. Ukrainian is the canonical copy (post_title /
 * post_excerpt / post_content); EN + PT live in _sm_{field}_{lang} postmeta and
 * are injected by SM_Product_I18n.
 *
 * Idempotent: identified by the _sm_seed_key = 'beetroot-powder' marker meta, so
 * re-running updates the same product instead of creating duplicates.
 *
 * Requires the source image at /var/www/html/wp-content/uploads/tmp-beetroot.jpg
 * (imported into the media library on first run, then reused).
 *
 * Run:  docker compose run --rm wpcli wp eval-file docker/seed-beetroot.php --allow-root
 */
defined( 'ABSPATH' ) || exit;

$SEED_KEY  = 'beetroot-powder';
$IMG_TMP   = WP_CONTENT_DIR . '/uploads/tmp-beetroot.jpg';
$CAT_SLUG  = 'freeze-dried-vegetables';
$USE_CASES = [ 'cooking', 'ready-meal', 'sweets' ];
$PRICES    = [ '50g' => '449', '100g' => '899' ];   // UAH
$WEIGHTS   = [ '50g' => '0.05', '100g' => '0.10' ]; // kg (Nova Poshta shipping)

/* ── Copy blocks ───────────────────────────────────────────────────────── */
$ua_name = 'Сублімований буряк, порошок';
$en_name = 'Freeze-dried Beetroot Powder';
$pt_name = 'Beterraba Liofilizada em Pó';

$ua_short = 'Сублімований буряк у порошку — 100% натуральний, без цукру та добавок. Додавайте до смузі, супів, випічки, соусів і десертів.';
$en_short = 'Freeze-dried beetroot powder — 100% natural, no sugar or additives. Add to smoothies, soups, baking, sauces and desserts.';
$pt_short = 'Beterraba liofilizada em pó — 100% natural, sem açúcar nem aditivos. Adicione a smoothies, sopas, produtos de pastelaria, molhos e sobremesas.';

$ua_desc = <<<HTML
<p><strong>Склад:</strong><br>100% сублімований буряк.</p>
<p>Без цукру, консервантів, барвників, підсилювачів смаку та ГМО.</p>

<h3>Спосіб використання</h3>
<p>Порошок можна додавати до:</p>
<ul>
<li>смузі та овочевих коктейлів;</li>
<li>крем-супів і соусів;</li>
<li>тіста для хліба, млинців і випічки;</li>
<li>макаронних виробів;</li>
<li>кондитерських виробів, кремів і начинок;</li>
<li>домашніх спецій та сухих сумішей;</li>
<li>корисних снеків і енергетичних батончиків.</li>
</ul>

<p>Буряк містить:</p>
<ul>
<li><strong>Фолієву кислоту (вітамін B9)</strong> — сприяє нормальному кровотворенню, поділу клітин і підтримці імунної системи.</li>
<li><strong>Вітамін C</strong> — допомагає підтримувати нормальну роботу імунної системи, бере участь у синтезі колагену та захищає клітини від окисного стресу.</li>
<li><strong>Вітаміни групи B (B1, B2, B3, B6)</strong> — необхідні для нормального енергетичного обміну та роботи нервової системи.</li>
<li><strong>Калій</strong> — сприяє нормальному функціонуванню м’язів, нервової системи та підтриманню нормального артеріального тиску.</li>
<li><strong>Магній</strong> — підтримує нормальну роботу м’язів, нервової системи та бере участь у багатьох ферментативних процесах.</li>
<li><strong>Залізо</strong> — необхідне для нормального транспорту кисню в організмі та утворення гемоглобіну.</li>
<li><strong>Марганець</strong> — бере участь у формуванні сполучної тканини та захисті клітин від окисного стресу.</li>
<li><strong>Кальцій</strong> — важливий для здоров’я кісток і зубів.</li>
<li><strong>Фосфор</strong> — підтримує здоров’я кісткової тканини та бере участь в енергетичному обміні.</li>
</ul>

<p>Крім вітамінів і мінералів, буряк є природним джерелом харчових волокон, антиоксидантів (зокрема беталаїнів, які надають буряку характерного насиченого кольору) та природних нітратів, що є природними сполуками рослинного походження.</p>

<p>Завдяки концентрованій формі сублімований порошок з буряка є зручним способом додати до раціону поживні речовини.</p>

<h3>Умови зберігання</h3>
<p>Зберігати в сухому, прохолодному місці, захищеному від прямих сонячних променів. Після відкриття упаковки щільно закривати, щоб уникнути потрапляння вологи.</p>

<h3>Термін придатності</h3>
<p>24 місяці за умови дотримання умов зберігання.</p>
HTML;

$en_desc = <<<HTML
<p><strong>Ingredients:</strong><br>100% freeze-dried beetroot.</p>
<p>No sugar, preservatives, colourings, flavour enhancers or GMOs.</p>

<h3>How to use</h3>
<p>The powder can be added to:</p>
<ul>
<li>smoothies and vegetable shakes;</li>
<li>cream soups and sauces;</li>
<li>dough for bread, pancakes and baking;</li>
<li>pasta;</li>
<li>confectionery, creams and fillings;</li>
<li>homemade spices and dry mixes;</li>
<li>healthy snacks and energy bars.</li>
</ul>

<p>Beetroot contains:</p>
<ul>
<li><strong>Folic acid (vitamin B9)</strong> — supports normal blood formation, cell division and the immune system.</li>
<li><strong>Vitamin C</strong> — helps maintain normal immune function, contributes to collagen synthesis and protects cells from oxidative stress.</li>
<li><strong>B vitamins (B1, B2, B3, B6)</strong> — needed for normal energy metabolism and nervous system function.</li>
<li><strong>Potassium</strong> — supports normal muscle and nervous system function and helps maintain normal blood pressure.</li>
<li><strong>Magnesium</strong> — supports normal muscle and nervous system function and takes part in many enzymatic processes.</li>
<li><strong>Iron</strong> — needed for normal oxygen transport in the body and the formation of haemoglobin.</li>
<li><strong>Manganese</strong> — contributes to the formation of connective tissue and protects cells from oxidative stress.</li>
<li><strong>Calcium</strong> — important for healthy bones and teeth.</li>
<li><strong>Phosphorus</strong> — supports healthy bone tissue and takes part in energy metabolism.</li>
</ul>

<p>In addition to vitamins and minerals, beetroot is a natural source of dietary fibre, antioxidants (in particular betalains, which give beetroot its characteristic rich colour) and natural nitrates — naturally occurring compounds of plant origin.</p>

<p>Thanks to its concentrated form, freeze-dried beetroot powder is a convenient way to add nutrients to your diet.</p>

<h3>Storage conditions</h3>
<p>Store in a dry, cool place, protected from direct sunlight. Once opened, close tightly to prevent moisture ingress.</p>

<h3>Shelf life</h3>
<p>24 months when storage conditions are observed.</p>
HTML;

$pt_desc = <<<HTML
<p><strong>Ingredientes:</strong><br>100% beterraba liofilizada.</p>
<p>Sem açúcar, conservantes, corantes, intensificadores de sabor nem OGM.</p>

<h3>Modo de utilização</h3>
<p>O pó pode ser adicionado a:</p>
<ul>
<li>smoothies e batidos de legumes;</li>
<li>cremes de sopa e molhos;</li>
<li>massas para pão, panquecas e pastelaria;</li>
<li>massas alimentícias;</li>
<li>produtos de confeitaria, cremes e recheios;</li>
<li>especiarias caseiras e misturas secas;</li>
<li>snacks saudáveis e barras energéticas.</li>
</ul>

<p>A beterraba contém:</p>
<ul>
<li><strong>Ácido fólico (vitamina B9)</strong> — contribui para a formação normal do sangue, a divisão celular e o sistema imunitário.</li>
<li><strong>Vitamina C</strong> — ajuda a manter o funcionamento normal do sistema imunitário, participa na síntese de colagénio e protege as células do stress oxidativo.</li>
<li><strong>Vitaminas do complexo B (B1, B2, B3, B6)</strong> — necessárias para o metabolismo energético normal e o funcionamento do sistema nervoso.</li>
<li><strong>Potássio</strong> — contribui para o funcionamento normal dos músculos e do sistema nervoso e para a manutenção de uma tensão arterial normal.</li>
<li><strong>Magnésio</strong> — apoia o funcionamento normal dos músculos e do sistema nervoso e participa em muitos processos enzimáticos.</li>
<li><strong>Ferro</strong> — necessário para o transporte normal de oxigénio no organismo e a formação de hemoglobina.</li>
<li><strong>Manganês</strong> — participa na formação do tecido conjuntivo e protege as células do stress oxidativo.</li>
<li><strong>Cálcio</strong> — importante para a saúde dos ossos e dos dentes.</li>
<li><strong>Fósforo</strong> — apoia a saúde do tecido ósseo e participa no metabolismo energético.</li>
</ul>

<p>Para além de vitaminas e minerais, a beterraba é uma fonte natural de fibra alimentar, antioxidantes (em particular as betalaínas, que conferem à beterraba a sua cor intensa característica) e nitratos naturais — compostos de origem vegetal que ocorrem naturalmente.</p>

<p>Graças à sua forma concentrada, o pó de beterraba liofilizada é uma forma prática de adicionar nutrientes à sua alimentação.</p>

<h3>Condições de armazenamento</h3>
<p>Conservar em local seco e fresco, protegido da luz solar direta. Após a abertura, fechar bem para evitar a entrada de humidade.</p>

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
	'post_name'    => 'freeze-dried-beetroot-powder', // English slug, like the other products
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
	$dest = trailingslashit( $up['path'] ) . 'freeze-dried-beetroot-powder.jpg';
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
