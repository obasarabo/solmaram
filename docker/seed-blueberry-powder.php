<?php
/**
 * Seed the "Freeze-dried Blueberry Powder" product (лохина).
 *
 * A variable product on the pa_vaga (Вага) package-size attribute with two
 * variations, 50 г / 100 г. Ukrainian is the canonical copy (post_title /
 * post_excerpt / post_content); EN + PT live in _sm_{field}_{lang} postmeta and
 * are injected by SM_Product_I18n.
 *
 * Idempotent: identified by the _sm_seed_key = 'blueberry-powder' marker meta.
 * Requires /var/www/html/wp-content/uploads/tmp-blueberry.jpg on first run.
 *
 * Run:  docker compose run --rm wpcli wp eval 'require "/var/www/html/wp-content/seed-blueberry-powder.php";' --allow-root
 */
defined( 'ABSPATH' ) || exit;

$SEED_KEY  = 'blueberry-powder';
$IMG_TMP   = WP_CONTENT_DIR . '/uploads/tmp-blueberry.jpg';
$SLUG      = 'freeze-dried-blueberry-powder';
$CAT_SLUG  = 'freeze-dried-fruits-berries';
$USE_CASES = [ 'snack', 'cooking', 'sweets' ];
$PRICES    = [ '50g' => '699', '100g' => '1399' ]; // UAH
$WEIGHTS   = [ '50g' => '0.05', '100g' => '0.10' ]; // kg (Nova Poshta shipping)

/* ── Copy blocks ───────────────────────────────────────────────────────── */
$ua_name = 'Сублімована лохина, порошок';
$en_name = 'Freeze-dried Blueberry Powder';
$pt_name = 'Mirtilo Liofilizado em Pó';

$ua_short = 'Сублімована лохина у порошку — 100% натуральна, без цукру та добавок. Ідеальна для смузі, десертів, випічки та натурального фарбування.';
$en_short = 'Freeze-dried blueberry powder — 100% natural, no sugar or additives. Ideal for smoothies, desserts, baking and natural colouring.';
$pt_short = 'Mirtilo liofilizado em pó — 100% natural, sem açúcar nem aditivos. Ideal para smoothies, sobremesas, pastelaria e coloração natural.';

$ua_desc = <<<HTML
<p><strong>Склад</strong><br>100% сублімована лохина.</p>

<p>Без ГМО, без доданого цукру, без консервантів та штучних добавок.</p>

<h3>Застосування</h3>
<p>Сублімований порошок з лохини чудово підходить для:</p>
<ul>
<li>смузі та коктейлів;</li>
<li>йогуртів, каш і граноли;</li>
<li>випічки та десертів;</li>
<li>кремів, мусів і начинок;</li>
<li>морозива;</li>
<li>шоколаду та цукерок;</li>
<li>соусів і напоїв;</li>
<li>натурального фарбування кремів, глазурі та тіста.</li>
</ul>

<p>Лохина є природним джерелом:</p>
<ul>
<li>антиоксидантів, зокрема антоціанів, які допомагають захищати клітини організму від окисного стресу;</li>
<li>вітаміну C, що підтримує нормальну роботу імунної системи та сприяє утворенню колагену;</li>
<li>вітаміну K, важливого для нормального згортання крові та здоров’я кісток;</li>
<li>марганцю, який бере участь в енергетичному обміні та підтримує нормальне функціонування організму;</li>
<li>харчових волокон, що сприяють нормальному травленню;</li>
<li>природних поліфенолів та інших біологічно активних сполук.</li>
</ul>

<h3>Умови зберігання</h3>
<p>Зберігати в сухому, прохолодному місці, подалі від прямих сонячних променів, у щільно закритій упаковці. Після відкриття рекомендується мінімізувати контакт продукту з вологою.</p>

<h3>Термін придатності</h3>
<p>24 місяці за умови дотримання умов зберігання.</p>
HTML;

$en_desc = <<<HTML
<p><strong>Ingredients</strong><br>100% freeze-dried blueberry.</p>

<p>No GMOs, no added sugar, no preservatives or artificial additives.</p>

<h3>Uses</h3>
<p>Freeze-dried blueberry powder is a great fit for:</p>
<ul>
<li>smoothies and shakes;</li>
<li>yogurts, porridges and granola;</li>
<li>baking and desserts;</li>
<li>creams, mousses and fillings;</li>
<li>ice cream;</li>
<li>chocolate and sweets;</li>
<li>sauces and drinks;</li>
<li>natural colouring of creams, glazes and dough.</li>
</ul>

<p>Blueberry is a natural source of:</p>
<ul>
<li>antioxidants, in particular anthocyanins, which help protect the body's cells from oxidative stress;</li>
<li>vitamin C, which supports normal immune function and contributes to collagen formation;</li>
<li>vitamin K, important for normal blood clotting and bone health;</li>
<li>manganese, which takes part in energy metabolism and supports normal body function;</li>
<li>dietary fibre, which supports normal digestion;</li>
<li>natural polyphenols and other biologically active compounds.</li>
</ul>

<h3>Storage conditions</h3>
<p>Store in a dry, cool place, away from direct sunlight, in tightly closed packaging. Once opened, it is recommended to minimise the product's contact with moisture.</p>

<h3>Shelf life</h3>
<p>24 months when storage conditions are observed.</p>
HTML;

$pt_desc = <<<HTML
<p><strong>Ingredientes</strong><br>100% mirtilo liofilizado.</p>

<p>Sem OGM, sem açúcar adicionado, sem conservantes nem aditivos artificiais.</p>

<h3>Utilização</h3>
<p>O pó de mirtilo liofilizado é perfeito para:</p>
<ul>
<li>smoothies e batidos;</li>
<li>iogurtes, papas e granola;</li>
<li>pastelaria e sobremesas;</li>
<li>cremes, mousses e recheios;</li>
<li>gelados;</li>
<li>chocolate e rebuçados;</li>
<li>molhos e bebidas;</li>
<li>coloração natural de cremes, coberturas e massas.</li>
</ul>

<p>O mirtilo é uma fonte natural de:</p>
<ul>
<li>antioxidantes, em particular antocianinas, que ajudam a proteger as células do organismo do stress oxidativo;</li>
<li>vitamina C, que apoia o funcionamento normal do sistema imunitário e contribui para a formação de colagénio;</li>
<li>vitamina K, importante para a coagulação normal do sangue e a saúde dos ossos;</li>
<li>manganês, que participa no metabolismo energético e apoia o funcionamento normal do organismo;</li>
<li>fibra alimentar, que apoia a digestão normal;</li>
<li>polifenóis naturais e outros compostos biologicamente ativos.</li>
</ul>

<h3>Condições de armazenamento</h3>
<p>Conservar em local seco e fresco, afastado da luz solar direta, em embalagem bem fechada. Após a abertura, recomenda-se minimizar o contacto do produto com a humidade.</p>

<h3>Prazo de validade</h3>
<p>24 meses, desde que sejam respeitadas as condições de armazenamento.</p>
HTML;

require __DIR__ . '/_seed-powder-common.php';
