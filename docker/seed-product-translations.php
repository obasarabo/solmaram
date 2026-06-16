<?php
/**
 * Seed UA product names + long/short descriptions (post_title, post_content,
 * post_excerpt) and EN/PT translations (postmeta).
 *
 * Default language is Ukrainian, so the canonical post_title / post_content /
 * post_excerpt hold the Ukrainian copy; English and Portuguese live in
 * _sm_{field}_{lang} postmeta and are injected by SM_Product_I18n.
 *
 * Safe to run multiple times — all operations are idempotent.
 * Run: wp eval-file /var/www/html/seed-product-translations.php --allow-root
 */

$products = array(
    25 => array(
        'ua_name'  => 'Сублімований горох',
        'en_name'  => 'Freeze-dried Peas',
        'pt_name'  => 'Ervilhas liofilizadas',
        'ua_short' => 'Ніжний, хрусткий сублімований горох — ідеальний перекус або добавка до супів та рагу.',
        'en_short' => 'Tender, crunchy freeze-dried peas — a perfect snack or addition to soups and stews.',
        'pt_short' => 'Ervilhas liofilizadas tenras e crocantes — o lanche perfeito ou adição a sopas e guisados.',
        'ua_desc'  => 'Хрусткий сублімований зелений горошок. Ідеальний для супів, салатів і перекусів.',
        'en_desc'  => 'Crispy freeze-dried green peas. Perfect for soups, salads, and snacking.',
        'pt_desc'  => 'Ervilhas verdes liofilizadas e crocantes. Perfeitas para sopas, saladas e lanches.',
    ),
    26 => array(
        'ua_name'  => 'Сублімована кукурудза',
        'en_name'  => 'Freeze-dried Sweet Corn',
        'pt_name'  => 'Milho doce liofilizado',
        'ua_short' => 'Солодка сублімована кукурудза зберігає натуральний смак і колір. Чудово підходить для каш і салатів.',
        'en_short' => 'Sweet freeze-dried corn preserves its natural flavour and colour. Great for porridges and salads.',
        'pt_short' => 'Milho doce liofilizado que preserva o sabor e a cor naturais. Ótimo para papas e saladas.',
        'ua_desc'  => 'Зерна солодкої кукурудзи, сублімовані для збереження натурального смаку та поживних речовин.',
        'en_desc'  => 'Sweet corn kernels freeze-dried to lock in natural flavour and nutrients.',
        'pt_desc'  => 'Grãos de milho doce liofilizados para preservar o sabor e os nutrientes naturais.',
    ),
    27 => array(
        'ua_name'  => 'Сублімована броколі',
        'en_name'  => 'Freeze-dried Broccoli',
        'pt_name'  => 'Brócolo liofilizado',
        'ua_short' => 'Сублімована броколі зберігає до 97% поживних речовин. Швидко відновлюється у воді.',
        'en_short' => 'Freeze-dried broccoli retains up to 97% of nutrients. Rehydrates quickly in water.',
        'pt_short' => 'Brócolo liofilizado retém até 97% dos nutrientes. Reidrata rapidamente em água.',
        'ua_desc'  => 'Багаті на поживні речовини суцвіття броколі, сублімовані для максимально тривалого зберігання.',
        'en_desc'  => 'Nutrient-rich broccoli florets, freeze-dried for maximum shelf life.',
        'pt_desc'  => 'Floretes de brócolo ricos em nutrientes, liofilizados para máxima durabilidade.',
    ),
    28 => array(
        'ua_name'  => 'Сублімована морква',
        'en_name'  => 'Freeze-dried Carrots',
        'pt_name'  => 'Cenoura liofilizada',
        'ua_short' => 'Хрустка сублімована морква з натуральною солодкістю. Ідеальна для снеків і перших страв.',
        'en_short' => 'Crunchy freeze-dried carrots with natural sweetness. Ideal for snacking and soups.',
        'pt_short' => 'Cenoura liofilizada crocante com doçura natural. Ideal para lanches e sopas.',
        'ua_desc'  => 'Хрусткі скибочки сублімованої моркви, ідеальні для приготування страв або перекусів.',
        'en_desc'  => 'Crunchy freeze-dried carrot slices, ideal for cooking or snacking.',
        'pt_desc'  => 'Fatias de cenoura liofilizadas e crocantes, ideais para cozinhar ou petiscar.',
    ),
    29 => array(
        'ua_name'  => 'Сублімований шпинат',
        'en_name'  => 'Freeze-dried Spinach',
        'pt_name'  => 'Espinafre liofilizado',
        'ua_short' => 'Насичений поживними речовинами сублімований шпинат для смузі, соусів і готових страв.',
        'en_short' => 'Nutrient-dense freeze-dried spinach for smoothies, sauces and ready meals.',
        'pt_short' => 'Espinafre liofilizado rico em nutrientes para smoothies, molhos e refeições prontas.',
        'ua_desc'  => 'Листя молодого шпинату, сублімоване на піку свіжості.',
        'en_desc'  => 'Baby spinach leaves freeze-dried at peak freshness.',
        'pt_desc'  => 'Folhas de espinafre baby liofilizadas no auge da frescura.',
    ),
    30 => array(
        'ua_name'  => 'Сублімована полуниця',
        'en_name'  => 'Freeze-dried Strawberries',
        'pt_name'  => 'Morango liofilizado',
        'ua_short' => 'Яскрава, солодка сублімована полуниця — чудовий перекус або топінг для десертів.',
        'en_short' => 'Bright, sweet freeze-dried strawberries — great snack or topping for desserts.',
        'pt_short' => 'Morangos liofilizados brilhantes e doces — ótimo lanche ou cobertura para sobremesas.',
        'ua_desc'  => 'Яскраво-червона полуниця з насиченим смаком, сублімована та готова до споживання.',
        'en_desc'  => 'Bright red strawberries with intense flavour, freeze-dried and ready to eat.',
        'pt_desc'  => 'Morangos vermelhos e vibrantes com sabor intenso, liofilizados e prontos a comer.',
    ),
    31 => array(
        'ua_name'  => 'Сублімована чорниця',
        'en_name'  => 'Freeze-dried Blueberries',
        'pt_name'  => 'Mirtilo liofilizado',
        'ua_short' => 'Сублімована чорниця, багата антиоксидантами. Хрустка, без цукру і консервантів.',
        'en_short' => 'Freeze-dried blueberries rich in antioxidants. Crunchy, no sugar, no preservatives.',
        'pt_short' => 'Mirtilos liofilizados ricos em antioxidantes. Crocantes, sem açúcar nem conservantes.',
        'ua_desc'  => 'Дика чорниця, насичена антиоксидантами, дбайливо сублімована.',
        'en_desc'  => 'Wild blueberries packed with antioxidants, gently freeze-dried.',
        'pt_desc'  => 'Mirtilos selvagens repletos de antioxidantes, delicadamente liofilizados.',
    ),
    32 => array(
        'ua_name'  => 'Сублімована малина',
        'en_name'  => 'Freeze-dried Raspberries',
        'pt_name'  => 'Framboesa liofilizada',
        'ua_short' => 'Кисло-солодка сублімована малина зберігає весь смак і аромат свіжих ягід.',
        'en_short' => 'Sweet-tart freeze-dried raspberries that preserve all the flavour of fresh berries.',
        'pt_short' => 'Framboesas liofilizadas agridoces que preservam todo o sabor das bagas frescas.',
        'ua_desc'  => 'Кисло-солодка сублімована малина — чудова для йогурту, каші чи випічки.',
        'en_desc'  => 'Tangy freeze-dried raspberries — great in yogurt, porridge, or baking.',
        'pt_desc'  => 'Framboesas liofilizadas agridoces — ótimas em iogurte, papas ou na pastelaria.',
    ),
    33 => array(
        'ua_name'  => 'Сублімовані яблука',
        'en_name'  => 'Freeze-dried Apple Slices',
        'pt_name'  => 'Fatias de maçã liofilizadas',
        'ua_short' => 'Хрусткі сублімовані скибочки яблук — натуральний снек для дітей і дорослих.',
        'en_short' => 'Crunchy freeze-dried apple slices — a natural snack for kids and adults alike.',
        'pt_short' => 'Fatias de maçã liofilizadas e crocantes — um lanche natural para crianças e adultos.',
        'ua_desc'  => 'Хрусткі скибочки яблук без додавання цукру, натурально солодкі.',
        'en_desc'  => 'Crispy apple slices with no added sugar, naturally sweet.',
        'pt_desc'  => 'Fatias de maçã crocantes sem açúcar adicionado, naturalmente doces.',
    ),
    34 => array(
        'ua_name'  => 'Сублімовані банани',
        'en_name'  => 'Freeze-dried Banana Chips',
        'pt_name'  => 'Chips de banana liofilizados',
        'ua_short' => 'Хрусткі сублімовані банани — солодкий перекус без цукру і консервантів.',
        'en_short' => 'Crispy freeze-dried banana chips — a sweet snack with no sugar or preservatives.',
        'pt_short' => 'Chips de banana liofilizados crocantes — um lanche doce sem açúcar nem conservantes.',
        'ua_desc'  => 'Легкі та хрусткі скибочки сублімованого банана — корисний перекус.',
        'en_desc'  => 'Light and crunchy freeze-dried banana slices, a healthy snack.',
        'pt_desc'  => 'Fatias de banana liofilizadas leves e crocantes — um lanche saudável.',
    ),
);

foreach ( $products as $id => $data ) {
    wp_update_post( array(
        'ID'           => $id,
        'post_title'   => $data['ua_name'],
        'post_excerpt' => $data['ua_short'],
        'post_content' => $data['ua_desc'],
    ) );

    update_post_meta( $id, '_sm_name_en',              $data['en_name'] );
    update_post_meta( $id, '_sm_short_description_en', $data['en_short'] );
    update_post_meta( $id, '_sm_description_en',       $data['en_desc'] );
    update_post_meta( $id, '_sm_name_pt',              $data['pt_name'] );
    update_post_meta( $id, '_sm_short_description_pt', $data['pt_short'] );
    update_post_meta( $id, '_sm_description_pt',       $data['pt_desc'] );

    echo "Updated: [{$id}] {$data['ua_name']}\n";
}

wp_cache_flush();
echo "Done.\n";
