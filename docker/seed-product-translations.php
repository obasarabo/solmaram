<?php
/**
 * Seed UA product names (post_title) and EN/PT translations (postmeta).
 *
 * Safe to run multiple times — all operations are idempotent.
 * Run: wp eval-file /var/www/html/seed-product-translations.php --allow-root
 */

$products = array(
    25 => array(
        'ua_name'  => 'Ліофілізований горох',
        'en_name'  => 'Freeze-dried Peas',
        'pt_name'  => 'Ervilhas liofilizadas',
        'ua_short' => 'Ніжний, хрусткий ліофілізований горох — ідеальний перекус або добавка до супів та рагу.',
        'en_short' => 'Tender, crunchy freeze-dried peas — a perfect snack or addition to soups and stews.',
        'pt_short' => 'Ervilhas liofilizadas tenras e crocantes — o lanche perfeito ou adição a sopas e guisados.',
    ),
    26 => array(
        'ua_name'  => 'Ліофілізована кукурудза',
        'en_name'  => 'Freeze-dried Sweet Corn',
        'pt_name'  => 'Milho doce liofilizado',
        'ua_short' => 'Солодка ліофілізована кукурудза зберігає натуральний смак і колір. Чудово підходить для каш і салатів.',
        'en_short' => 'Sweet freeze-dried corn preserves its natural flavour and colour. Great for porridges and salads.',
        'pt_short' => 'Milho doce liofilizado que preserva o sabor e a cor naturais. Ótimo para papas e saladas.',
    ),
    27 => array(
        'ua_name'  => 'Ліофілізована броколі',
        'en_name'  => 'Freeze-dried Broccoli',
        'pt_name'  => 'Brócolo liofilizado',
        'ua_short' => 'Ліофілізована броколі зберігає до 97% поживних речовин. Швидко відновлюється у воді.',
        'en_short' => 'Freeze-dried broccoli retains up to 97% of nutrients. Rehydrates quickly in water.',
        'pt_short' => 'Brócolo liofilizado retém até 97% dos nutrientes. Reidrata rapidamente em água.',
    ),
    28 => array(
        'ua_name'  => 'Ліофілізована морква',
        'en_name'  => 'Freeze-dried Carrots',
        'pt_name'  => 'Cenoura liofilizada',
        'ua_short' => 'Хрустка ліофілізована морква з натуральною солодкістю. Ідеальна для снеків і перших страв.',
        'en_short' => 'Crunchy freeze-dried carrots with natural sweetness. Ideal for snacking and soups.',
        'pt_short' => 'Cenoura liofilizada crocante com doçura natural. Ideal para lanches e sopas.',
    ),
    29 => array(
        'ua_name'  => 'Ліофілізований шпинат',
        'en_name'  => 'Freeze-dried Spinach',
        'pt_name'  => 'Espinafre liofilizado',
        'ua_short' => 'Насичений поживними речовинами ліофілізований шпинат для смузі, соусів і готових страв.',
        'en_short' => 'Nutrient-dense freeze-dried spinach for smoothies, sauces and ready meals.',
        'pt_short' => 'Espinafre liofilizado rico em nutrientes para smoothies, molhos e refeições prontas.',
    ),
    30 => array(
        'ua_name'  => 'Ліофілізована полуниця',
        'en_name'  => 'Freeze-dried Strawberries',
        'pt_name'  => 'Morango liofilizado',
        'ua_short' => 'Яскрава, солодка ліофілізована полуниця — чудовий перекус або топінг для десертів.',
        'en_short' => 'Bright, sweet freeze-dried strawberries — great snack or topping for desserts.',
        'pt_short' => 'Morangos liofilizados brilhantes e doces — ótimo lanche ou cobertura para sobremesas.',
    ),
    31 => array(
        'ua_name'  => 'Ліофілізована чорниця',
        'en_name'  => 'Freeze-dried Blueberries',
        'pt_name'  => 'Mirtilo liofilizado',
        'ua_short' => 'Ліофілізована чорниця, багата антиоксидантами. Хрустка, без цукру і консервантів.',
        'en_short' => 'Freeze-dried blueberries rich in antioxidants. Crunchy, no sugar, no preservatives.',
        'pt_short' => 'Mirtilos liofilizados ricos em antioxidantes. Crocantes, sem açúcar nem conservantes.',
    ),
    32 => array(
        'ua_name'  => 'Ліофілізована малина',
        'en_name'  => 'Freeze-dried Raspberries',
        'pt_name'  => 'Framboesa liofilizada',
        'ua_short' => 'Кисло-солодка ліофілізована малина зберігає весь смак і аромат свіжих ягід.',
        'en_short' => 'Sweet-tart freeze-dried raspberries that preserve all the flavour of fresh berries.',
        'pt_short' => 'Framboesas liofilizadas agridoces que preservam todo o sabor das bagas frescas.',
    ),
    33 => array(
        'ua_name'  => 'Ліофілізовані яблука',
        'en_name'  => 'Freeze-dried Apple Slices',
        'pt_name'  => 'Fatias de maçã liofilizadas',
        'ua_short' => 'Хрусткі ліофілізовані скибочки яблук — натуральний снек для дітей і дорослих.',
        'en_short' => 'Crunchy freeze-dried apple slices — a natural snack for kids and adults alike.',
        'pt_short' => 'Fatias de maçã liofilizadas e crocantes — um lanche natural para crianças e adultos.',
    ),
    34 => array(
        'ua_name'  => 'Ліофілізовані банани',
        'en_name'  => 'Freeze-dried Banana Chips',
        'pt_name'  => 'Chips de banana liofilizados',
        'ua_short' => 'Хрусткі ліофілізовані банани — солодкий перекус без цукру і консервантів.',
        'en_short' => 'Crispy freeze-dried banana chips — a sweet snack with no sugar or preservatives.',
        'pt_short' => 'Chips de banana liofilizados crocantes — um lanche doce sem açúcar nem conservantes.',
    ),
);

foreach ( $products as $id => $data ) {
    wp_update_post( array(
        'ID'           => $id,
        'post_title'   => $data['ua_name'],
        'post_excerpt' => $data['ua_short'],
    ) );

    update_post_meta( $id, '_sm_name_en',              $data['en_name'] );
    update_post_meta( $id, '_sm_short_description_en', $data['en_short'] );
    update_post_meta( $id, '_sm_name_pt',              $data['pt_name'] );
    update_post_meta( $id, '_sm_short_description_pt', $data['pt_short'] );

    echo "Updated: [{$id}] {$data['ua_name']}\n";
}

wp_cache_flush();
echo "Done.\n";
