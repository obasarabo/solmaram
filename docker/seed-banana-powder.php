<?php
/**
 * Seed the "Freeze-dried Banana Powder" product.
 *
 * A variable product on the pa_vaga (Вага) package-size attribute with two
 * variations, 50 г / 100 г. Ukrainian is the canonical copy (post_title /
 * post_excerpt / post_content); EN + PT live in _sm_{field}_{lang} postmeta and
 * are injected by SM_Product_I18n.
 *
 * Idempotent: identified by the _sm_seed_key = 'banana-powder' marker meta.
 * Requires /var/www/html/wp-content/uploads/tmp-banana.jpg on first run.
 *
 * Run:  docker compose run --rm wpcli wp eval 'require "/var/www/html/wp-content/seed-banana-powder.php";' --allow-root
 */
defined( 'ABSPATH' ) || exit;

$SEED_KEY  = 'banana-powder';
$IMG_TMP   = WP_CONTENT_DIR . '/uploads/tmp-banana.jpg';
$SLUG      = 'freeze-dried-banana-powder';
$CAT_SLUG  = 'freeze-dried-fruits-berries';
$USE_CASES = [ 'snack', 'cooking', 'sweets' ];
$PRICES    = [ '50g' => '699', '100g' => '1399' ]; // UAH
$WEIGHTS   = [ '50g' => '0.05', '100g' => '0.10' ]; // kg (Nova Poshta shipping)

/* ── Copy blocks ───────────────────────────────────────────────────────── */
$ua_name = 'Сублімований банан, порошок';
$en_name = 'Freeze-dried Banana Powder';
$pt_name = 'Banana Liofilizada em Pó';

$ua_short = 'Сублімований банан у порошку — 100% натуральний, без цукру та добавок. Ідеальний для смузі, каш, випічки, десертів і спортивного харчування.';
$en_short = 'Freeze-dried banana powder — 100% natural, no sugar or additives. Ideal for smoothies, porridges, baking, desserts and sports nutrition.';
$pt_short = 'Banana liofilizada em pó — 100% natural, sem açúcar nem aditivos. Ideal para smoothies, papas, pastelaria, sobremesas e nutrição desportiva.';

$ua_desc = <<<HTML
<p><strong>Склад</strong><br>100% сублімований банан.</p>

<p>Без ГМО, без доданого цукру, без консервантів та штучних добавок.</p>

<p>Його можна використовувати для:</p>
<ul>
<li>приготування смузі, молочних коктейлів та інших напоїв;</li>
<li>додавання до каш, мюслі, граноли та йогуртів;</li>
<li>випікання кексів, печива, бісквітів, млинців і вафель;</li>
<li>приготування кремів, мусів, начинок і десертів;</li>
<li>виготовлення морозива, сорбетів і заморожених десертів;</li>
<li>протеїнових коктейлів та спортивного харчування;</li>
<li>домашніх цукерок, шоколаду, енергетичних батончиків.</li>
</ul>

<p>Банан є природним джерелом:</p>
<ul>
<li>калію, який сприяє нормальній роботі м’язів, нервової системи та підтриманню нормального артеріального тиску;</li>
<li>вітаміну B6, що бере участь у нормальному енергетичному обміні та підтримує функціонування нервової й імунної систем;</li>
<li>вітаміну C, який допомагає підтримувати нормальну роботу імунної системи та захищає клітини від окисного стресу;</li>
<li>харчових волокон, що сприяють нормальному травленню та забезпечують триваліше відчуття ситості;</li>
<li>природних вуглеводів, які є джерелом енергії для організму.</li>
</ul>

<h3>Умови зберігання</h3>
<p>Зберігати в сухому, прохолодному місці, подалі від прямих сонячних променів, у щільно закритій упаковці. Після відкриття рекомендується мінімізувати контакт продукту з вологою.</p>

<h3>Термін зберігання</h3>
<p>24 місяці за умови дотримання умов зберігання.</p>
HTML;

$en_desc = <<<HTML
<p><strong>Ingredients</strong><br>100% freeze-dried banana.</p>

<p>No GMOs, no added sugar, no preservatives or artificial additives.</p>

<p>It can be used for:</p>
<ul>
<li>making smoothies, milkshakes and other drinks;</li>
<li>adding to porridges, muesli, granola and yogurts;</li>
<li>baking muffins, biscuits, sponge cakes, pancakes and waffles;</li>
<li>making creams, mousses, fillings and desserts;</li>
<li>making ice cream, sorbets and frozen desserts;</li>
<li>protein shakes and sports nutrition;</li>
<li>homemade sweets, chocolate and energy bars.</li>
</ul>

<p>Banana is a natural source of:</p>
<ul>
<li>potassium, which supports normal muscle and nervous system function and helps maintain normal blood pressure;</li>
<li>vitamin B6, which takes part in normal energy metabolism and supports the nervous and immune systems;</li>
<li>vitamin C, which helps maintain normal immune function and protects cells from oxidative stress;</li>
<li>dietary fibre, which supports normal digestion and provides a longer feeling of fullness;</li>
<li>natural carbohydrates, which are a source of energy for the body.</li>
</ul>

<h3>Storage conditions</h3>
<p>Store in a dry, cool place, away from direct sunlight, in tightly closed packaging. Once opened, it is recommended to minimise the product's contact with moisture.</p>

<h3>Shelf life</h3>
<p>24 months when storage conditions are observed.</p>
HTML;

$pt_desc = <<<HTML
<p><strong>Ingredientes</strong><br>100% banana liofilizada.</p>

<p>Sem OGM, sem açúcar adicionado, sem conservantes nem aditivos artificiais.</p>

<p>Pode ser utilizada para:</p>
<ul>
<li>preparar smoothies, batidos de leite e outras bebidas;</li>
<li>adicionar a papas, muesli, granola e iogurtes;</li>
<li>cozer queques, bolachas, pão de ló, panquecas e waffles;</li>
<li>preparar cremes, mousses, recheios e sobremesas;</li>
<li>fazer gelados, sorvetes e sobremesas geladas;</li>
<li>batidos proteicos e nutrição desportiva;</li>
<li>rebuçados caseiros, chocolate e barras energéticas.</li>
</ul>

<p>A banana é uma fonte natural de:</p>
<ul>
<li>potássio, que apoia o funcionamento normal dos músculos e do sistema nervoso e ajuda a manter uma tensão arterial normal;</li>
<li>vitamina B6, que participa no metabolismo energético normal e apoia o funcionamento dos sistemas nervoso e imunitário;</li>
<li>vitamina C, que ajuda a manter o funcionamento normal do sistema imunitário e protege as células do stress oxidativo;</li>
<li>fibra alimentar, que apoia a digestão normal e proporciona uma sensação de saciedade mais duradoura;</li>
<li>hidratos de carbono naturais, que são uma fonte de energia para o organismo.</li>
</ul>

<h3>Condições de armazenamento</h3>
<p>Conservar em local seco e fresco, afastado da luz solar direta, em embalagem bem fechada. Após a abertura, recomenda-se minimizar o contacto do produto com a humidade.</p>

<h3>Prazo de validade</h3>
<p>24 meses, desde que sejam respeitadas as condições de armazenamento.</p>
HTML;

require __DIR__ . '/_seed-powder-common.php';
