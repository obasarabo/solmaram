<?php
/**
 * Seed language-specific About SolMaram text.
 * Source: SolMaram_About_Us.docx (English)
 *
 * Run: wp eval-file /var/www/html/seed-about-text.php --allow-root
 *
 * Text is stored as WP options (sm_about_text_en, sm_about_text_pt).
 * The UA default is stored in the Customizer theme mod sm_about_text.
 * front-page.php reads the language-specific option first, falls back
 * to the theme mod.
 */

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

// PT placeholder — translate from EN when ready
// update_option( 'sm_about_text_pt', $pt_about );
