<?php
/**
 * Seed language-specific About SolMaram text.
 * Source (EN): SolMaram_About_Us.docx
 * UA/PT: translated from English source.
 *
 * Run: wp eval-file /var/www/html/seed-about-text.php --allow-root
 *
 * Text is stored as WP options (sm_about_text_en, sm_about_text_pt).
 * The UA default is stored in the Customizer theme mod sm_about_text.
 * front-page.php reads the language-specific option first, falls back
 * to the theme mod.
 */

/* ── Ukrainian (default — stored in Customizer theme mod) ─────────── */
$ua_about = '<p>Енергія природи, збережена на піку свіжості.</p>

<p>SolMaram — український виробник натуральних сублімованих овочів, фруктів та ягід. Ми контролюємо кожен етап процесу — від вибору сировини до запечатування готового пакунку — щоб лише природа потрапляла на ваш стіл.</p>

<p>Ми також є сертифікованим імпортером ЄС преміальних сублімованих продуктів, що привозимо найкраще з Європи нашим покупцям.</p>

<h3>Наші принципи</h3>
<ul>
  <li><strong>Власне виробництво</strong> — Повний контроль якості від сировини до готового пакунку. Без компромісів.</li>
  <li><strong>Сертифікований імпорт ЄС</strong> — Продукти партнерів від перевірених європейських виробників, що відповідають суворим стандартам ЄС.</li>
  <li><strong>100% натуральне</strong> — Сублімація зберігає смак, колір і поживні речовини. Без добавок і консервантів.</li>
</ul>

<h3>Значення назви</h3>
<p><strong>Sol</strong> — сонце, енергія, природа<br><strong>Maram</strong> — вершина, прагнення, наміри</p>
<p>Разом: продукти з енергією природи — для свідомого та здорового способу життя.</p>

<blockquote><p>«Від поля до сублімації — чиста природа, нічого більше.»</p></blockquote>';

set_theme_mod( 'sm_about_text', $ua_about );
echo "UA about text saved (theme mod).\n";

/* ── English ──────────────────────────────────────────────────────── */
$en_about = '<p>Nature\'s energy, captured at its peak.</p>

<p>SolMaram is a Ukrainian producer of natural freeze-dried vegetables, fruits, and berries. We oversee every step of the process — from sourcing raw ingredients to sealing the final package — so that nothing but nature reaches your table.</p>

<p>We are also an EU-certified importer of premium freeze-dried products, bringing the best of European quality to our customers.</p>

<h3>What we stand for</h3>
<ul>
  <li><strong>Own production</strong> — Full quality control from raw material to finished pack. No shortcuts, no compromise.</li>
  <li><strong>EU-certified import</strong> — Certified partner products sourced from verified EU producers, meeting rigorous European standards.</li>
  <li><strong>100% natural</strong> — Freeze-drying preserves flavour, colour, and nutrients. No additives, no preservatives.</li>
</ul>

<h3>The meaning behind the name</h3>
<p><strong>Sol</strong> — sun, energy, nature<br><strong>Maram</strong> — peak, aspiration, intention</p>
<p>Together: products with the energy of nature — for a life lived with purpose.</p>

<blockquote><p>"From field to freeze-dry — pure nature, nothing more."</p></blockquote>';

update_option( 'sm_about_text_en', $en_about );
echo "EN about text saved.\n";

/* ── Portuguese ───────────────────────────────────────────────────── */
$pt_about = '<p>A energia da natureza, capturada no seu auge.</p>

<p>A SolMaram é um produtor ucraniano de vegetais, frutas e bagas liofilizados de forma natural. Supervisionamos cada etapa do processo — desde o abastecimento de ingredientes crus até à selagem da embalagem final — para que apenas a natureza chegue à sua mesa.</p>

<p>Somos também um importador certificado pela UE de produtos liofilizados premium, trazendo o melhor da qualidade europeia aos nossos clientes.</p>

<h3>O que defendemos</h3>
<ul>
  <li><strong>Produção própria</strong> — Controlo total de qualidade da matéria-prima ao produto final. Sem atalhos, sem compromissos.</li>
  <li><strong>Importação certificada pela UE</strong> — Produtos de parceiros provenientes de produtores europeus verificados, cumprindo rigorosos padrões europeus.</li>
  <li><strong>100% natural</strong> — A liofilização preserva o sabor, a cor e os nutrientes. Sem aditivos, sem conservantes.</li>
</ul>

<h3>O significado do nome</h3>
<p><strong>Sol</strong> — sol, energia, natureza<br><strong>Maram</strong> — pico, aspiração, intenção</p>
<p>Juntos: produtos com a energia da natureza — para uma vida vivida com propósito.</p>

<blockquote><p>«Do campo à liofilização — natureza pura, nada mais.»</p></blockquote>';

update_option( 'sm_about_text_pt', $pt_about );
echo "PT about text saved.\n";
