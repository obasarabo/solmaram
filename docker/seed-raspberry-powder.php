<?php
/**
 * Seed the "Freeze-dried Raspberry Powder" product.
 *
 * A variable product on the pa_vaga (Вага) package-size attribute with two
 * variations, 50 г / 100 г. Ukrainian is the canonical copy (post_title /
 * post_excerpt / post_content); EN + PT live in _sm_{field}_{lang} postmeta and
 * are injected by SM_Product_I18n.
 *
 * Idempotent: identified by the _sm_seed_key = 'raspberry-powder' marker meta,
 * so re-running updates the same product instead of creating duplicates.
 *
 * Requires the source image at /var/www/html/wp-content/uploads/tmp-raspberry.jpg
 * (imported into the media library on first run, then reused).
 *
 * Run:  docker compose run --rm wpcli wp eval-file docker/seed-raspberry-powder.php --allow-root
 */
defined( 'ABSPATH' ) || exit;

$SEED_KEY  = 'raspberry-powder';
$IMG_TMP   = WP_CONTENT_DIR . '/uploads/tmp-raspberry.jpg';
$SLUG      = 'freeze-dried-raspberry-powder';
$CAT_SLUG  = 'freeze-dried-fruits-berries';
$USE_CASES = [ 'snack', 'cooking', 'sweets' ];
$PRICES    = [ '50g' => '699', '100g' => '1399' ]; // UAH
$WEIGHTS   = [ '50g' => '0.05', '100g' => '0.10' ]; // kg (Nova Poshta shipping)

/* ── Copy blocks ───────────────────────────────────────────────────────── */
$ua_name = 'Сублімована малина, порошок';
$en_name = 'Freeze-dried Raspberry Powder';
$pt_name = 'Framboesa Liofilizada em Pó';

$ua_short = 'Сублімована малина у порошку (просіяна пудра) — 100% натуральна, без цукру та добавок. Ідеальна для десертів, випічки, напоїв і натурального фарбування.';
$en_short = 'Freeze-dried raspberry powder (sifted) — 100% natural, no sugar or additives. Ideal for desserts, baking, drinks and natural colouring.';
$pt_short = 'Framboesa liofilizada em pó (peneirada) — 100% natural, sem açúcar nem aditivos. Ideal para sobremesas, pastelaria, bebidas e coloração natural.';

$ua_desc = <<<HTML
<p><strong>Склад:</strong><br>100% сублімована малина.<br>Просіяна пудра.</p>

<p>Без цукру, консервантів, підсилювачів смаку та ГМО.</p>

<p>Сублімований порошок з малини можна додавати до:</p>
<ul>
<li>кондитерських виробів: тортів, тістечок, капкейків, макаронів, еклерів;</li>
<li>кремів, мусів, чизкейків, панакоти та інших десертів;</li>
<li>шоколаду, цукерок, маршмелоу та карамелі;</li>
<li>глазурі, ганашу, білкових кремів і начинок;</li>
<li>морозива, сорбетів і заморожених десертів;</li>
<li>смузі, молочних коктейлів, лимонадів, чаю та інших напоїв;</li>
<li>йогуртів, кефіру, сиру, граноли та вівсяних каш;</li>
<li>випічки: печива, кексів, бісквітів, млинців і вафель;</li>
<li>соусів, фруктових пюре та ягідних топінгів;</li>
<li>декорування десертів і створення натурального кольору без штучних барвників.</li>
</ul>

<h3>Корисні властивості</h3>
<ul>
<li><strong>Вітамін С</strong> — сприяє нормальній роботі імунної системи, підтримує утворення колагену, допомагає захищати клітини від оксидативного стресу.</li>
<li><strong>Вітамін К</strong> — бере участь у нормальному згортанні крові та підтримці здоров’я кісток.</li>
<li><strong>Вітамін Е</strong> — природний антиоксидант, який допомагає захищати клітини організму від дії вільних радикалів.</li>
<li><strong>Вітаміни групи В (В1, В2, В3, В6, фолієва кислота)</strong> — сприяють нормальному енергетичному обміну та підтримують роботу нервової системи.</li>
<li><strong>Калій</strong> — підтримує нормальну роботу серця, м’язів і нервової системи.</li>
<li><strong>Магній</strong> — сприяє зменшенню втоми та підтримує нормальну функцію м’язів.</li>
<li><strong>Марганець</strong> — бере участь у формуванні сполучної тканини та допомагає захищати клітини від оксидативного стресу.</li>
<li><strong>Залізо</strong> — сприяє нормальному транспорту кисню в організмі.</li>
<li><strong>Харчові волокна</strong> — підтримують нормальне травлення та сприяють тривалому відчуттю ситості.</li>
</ul>

<ul>
<li>Є природним джерелом антиоксидантів, зокрема антоціанів та поліфенолів.</li>
<li>Допомагає урізноманітнити раціон натуральними рослинними компонентами.</li>
<li>Зберігає насичений ягідний смак, аромат і природний колір.</li>
<li>Не містить доданого цукру, барвників, ароматизаторів і консервантів.</li>
<li>Підходить для здорового та збалансованого харчування.</li>
</ul>

<h3>Умови зберігання</h3>
<p>Зберігати в сухому, прохолодному місці при температурі до +25 °C, у щільно закритій упаковці, захищаючи від вологи та прямих сонячних променів.</p>

<h3>Термін придатності</h3>
<p>24 місяці за умови дотримання умов зберігання.</p>
HTML;

$en_desc = <<<HTML
<p><strong>Ingredients:</strong><br>100% freeze-dried raspberry.<br>Sifted powder.</p>

<p>No sugar, preservatives, flavour enhancers or GMOs.</p>

<p>Freeze-dried raspberry powder can be added to:</p>
<ul>
<li>confectionery: cakes, pastries, cupcakes, macarons, éclairs;</li>
<li>creams, mousses, cheesecakes, panna cotta and other desserts;</li>
<li>chocolate, sweets, marshmallows and caramel;</li>
<li>glazes, ganache, meringue creams and fillings;</li>
<li>ice cream, sorbets and frozen desserts;</li>
<li>smoothies, milkshakes, lemonades, tea and other drinks;</li>
<li>yogurts, kefir, cottage cheese, granola and oat porridges;</li>
<li>baking: biscuits, muffins, sponge cakes, pancakes and waffles;</li>
<li>sauces, fruit purées and berry toppings;</li>
<li>decorating desserts and creating natural colour without artificial dyes.</li>
</ul>

<h3>Beneficial properties</h3>
<ul>
<li><strong>Vitamin C</strong> — supports normal immune function, contributes to collagen formation and helps protect cells from oxidative stress.</li>
<li><strong>Vitamin K</strong> — takes part in normal blood clotting and the maintenance of healthy bones.</li>
<li><strong>Vitamin E</strong> — a natural antioxidant that helps protect the body's cells from the effects of free radicals.</li>
<li><strong>B vitamins (B1, B2, B3, B6, folic acid)</strong> — contribute to normal energy metabolism and support nervous system function.</li>
<li><strong>Potassium</strong> — supports normal heart, muscle and nervous system function.</li>
<li><strong>Magnesium</strong> — helps reduce tiredness and supports normal muscle function.</li>
<li><strong>Manganese</strong> — takes part in the formation of connective tissue and helps protect cells from oxidative stress.</li>
<li><strong>Iron</strong> — contributes to normal oxygen transport in the body.</li>
<li><strong>Dietary fibre</strong> — supports normal digestion and contributes to a lasting feeling of fullness.</li>
</ul>

<ul>
<li>A natural source of antioxidants, in particular anthocyanins and polyphenols.</li>
<li>Helps diversify the diet with natural plant components.</li>
<li>Preserves the rich berry taste, aroma and natural colour.</li>
<li>Contains no added sugar, colourings, flavourings or preservatives.</li>
<li>Suitable for a healthy and balanced diet.</li>
</ul>

<h3>Storage conditions</h3>
<p>Store in a dry, cool place at a temperature up to +25 °C, in tightly closed packaging, protected from moisture and direct sunlight.</p>

<h3>Shelf life</h3>
<p>24 months when storage conditions are observed.</p>
HTML;

$pt_desc = <<<HTML
<p><strong>Ingredientes:</strong><br>100% framboesa liofilizada.<br>Pó peneirado.</p>

<p>Sem açúcar, conservantes, intensificadores de sabor nem OGM.</p>

<p>O pó de framboesa liofilizada pode ser adicionado a:</p>
<ul>
<li>produtos de confeitaria: bolos, pastéis, cupcakes, macarons, éclairs;</li>
<li>cremes, mousses, cheesecakes, panna cotta e outras sobremesas;</li>
<li>chocolate, rebuçados, marshmallows e caramelo;</li>
<li>coberturas, ganache, cremes de merengue e recheios;</li>
<li>gelados, sorvetes e sobremesas geladas;</li>
<li>smoothies, batidos de leite, limonadas, chá e outras bebidas;</li>
<li>iogurtes, kefir, queijo fresco, granola e papas de aveia;</li>
<li>pastelaria: bolachas, queques, pão de ló, panquecas e waffles;</li>
<li>molhos, purés de fruta e coberturas de frutos vermelhos;</li>
<li>decoração de sobremesas e criação de cor natural sem corantes artificiais.</li>
</ul>

<h3>Propriedades benéficas</h3>
<ul>
<li><strong>Vitamina C</strong> — contribui para o funcionamento normal do sistema imunitário, apoia a formação de colagénio e ajuda a proteger as células do stress oxidativo.</li>
<li><strong>Vitamina K</strong> — participa na coagulação normal do sangue e na manutenção de ossos saudáveis.</li>
<li><strong>Vitamina E</strong> — um antioxidante natural que ajuda a proteger as células do organismo da ação dos radicais livres.</li>
<li><strong>Vitaminas do complexo B (B1, B2, B3, B6, ácido fólico)</strong> — contribuem para o metabolismo energético normal e apoiam o funcionamento do sistema nervoso.</li>
<li><strong>Potássio</strong> — apoia o funcionamento normal do coração, dos músculos e do sistema nervoso.</li>
<li><strong>Magnésio</strong> — ajuda a reduzir o cansaço e apoia a função muscular normal.</li>
<li><strong>Manganês</strong> — participa na formação do tecido conjuntivo e ajuda a proteger as células do stress oxidativo.</li>
<li><strong>Ferro</strong> — contribui para o transporte normal de oxigénio no organismo.</li>
<li><strong>Fibra alimentar</strong> — apoia a digestão normal e contribui para uma sensação de saciedade duradoura.</li>
</ul>

<ul>
<li>É uma fonte natural de antioxidantes, em particular antocianinas e polifenóis.</li>
<li>Ajuda a diversificar a alimentação com componentes vegetais naturais.</li>
<li>Preserva o sabor intenso dos frutos vermelhos, o aroma e a cor natural.</li>
<li>Não contém açúcar adicionado, corantes, aromatizantes nem conservantes.</li>
<li>Adequado para uma alimentação saudável e equilibrada.</li>
</ul>

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
	$dest = trailingslashit( $up['path'] ) . 'freeze-dried-raspberry-powder.jpg';
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
