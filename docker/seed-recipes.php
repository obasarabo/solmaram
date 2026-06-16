<?php
/**
 * Seed two recipes in UA (default), EN and PT as Polylang translations.
 * Run: wp eval-file /var/www/html/seed-recipes.php --allow-root
 */

/* ══════════════════════════════════════════════════════════════════
 * Recipe data
 * ═══════════════════════════════════════════════════════════════ */
$recipes = [

    /* ── Recipe 1: Berry Smoothie Bowl ────────────────────────── */
    [
        'ua' => [
            'title'       => 'Смузі-боул з сублімованими ягодами',
            'excerpt'     => 'Яскравий і поживний сніданок за 5 хвилин — без варіння і зайвого цукру.',
            'content'     => '<p>Сублімовані ягоди розкривають весь свій смак у кремовій основі зі сметани або йогурту. Страва готується за кілька хвилин і виглядає як у ресторані.</p>',
            'ingredients' => [
                '150 г натурального йогурту або сметани',
                '2 ст. л. сублімованої полуниці',
                '1 ст. л. сублімованої чорниці',
                '1 банан (для основи)',
                '1 ч. л. меду',
                'Мюслі або гранола для посипки',
            ],
            'steps' => [
                'Збийте йогурт з бананом у блендері до однорідної маси.',
                'Вилийте основу в глибоку тарілку.',
                'Рівномірно розкладіть зверху сублімовані ягоди.',
                'Полийте медом і посипте гранолою. Подавайте одразу.',
            ],
        ],
        'en' => [
            'title'       => 'Freeze-dried Berry Smoothie Bowl',
            'excerpt'     => 'A vibrant, nutritious breakfast ready in 5 minutes — no cooking, no added sugar.',
            'content'     => '<p>Freeze-dried berries unlock their full flavour in a creamy yogurt base. Ready in minutes and looks like it came from a café.</p>',
            'ingredients' => [
                '150 g natural yogurt or sour cream',
                '2 tbsp freeze-dried strawberries',
                '1 tbsp freeze-dried blueberries',
                '1 banana (for the base)',
                '1 tsp honey',
                'Granola or muesli for topping',
            ],
            'steps' => [
                'Blend yogurt with banana until smooth.',
                'Pour the base into a deep bowl.',
                'Scatter freeze-dried berries evenly on top.',
                'Drizzle with honey and top with granola. Serve immediately.',
            ],
        ],
        'pt' => [
            'title'       => 'Smoothie Bowl com Frutos Vermelhos Liofilizados',
            'excerpt'     => 'Um pequeno-almoço vibrante e nutritivo pronto em 5 minutos — sem cozinhar, sem açúcar adicionado.',
            'content'     => '<p>Os frutos vermelhos liofilizados libertam todo o seu sabor numa base cremosa de iogurte. Pronto em minutos e com aspeto de café gourmet.</p>',
            'ingredients' => [
                '150 g de iogurte natural ou natas azedas',
                '2 c. sopa de morangos liofilizados',
                '1 c. sopa de mirtilos liofilizados',
                '1 banana (para a base)',
                '1 c. chá de mel',
                'Granola ou muesli para cobrir',
            ],
            'steps' => [
                'Misture o iogurte com a banana no liquidificador até obter uma mistura homogénea.',
                'Deite a base numa tigela funda.',
                'Espalhe os frutos vermelhos liofilizados por cima.',
                'Regue com mel e cubra com granola. Sirva imediatamente.',
            ],
        ],
        'prep_time' => '5 хв / 5 min',
        'servings'  => '1',
        'products'  => [30, 31], // strawberries, blueberries
    ],

    /* ── Recipe 2: Vegetable Soup ──────────────────────────────── */
    [
        'ua' => [
            'title'       => 'Швидкий овочевий суп із сублімованими овочами',
            'excerpt'     => 'Ситний суп із тривалим терміном зберігання інгредієнтів — ідеально для кемпінгу та зайнятих буднів.',
            'content'     => '<p>Сублімовані овочі відновлюються у гарячій воді за кілька хвилин і зберігають смак та поживність свіжих. Суп виходить насиченим і ароматним без тривалого приготування.</p>',
            'ingredients' => [
                '1 л курячого або овочевого бульйону',
                '2 ст. л. сублімованої моркви',
                '2 ст. л. сублімованого горошку',
                '1 ст. л. сублімованого шпинату',
                '50 г дрібної локшини або вермішелі',
                'Сіль, перець, зелень за смаком',
            ],
            'steps' => [
                'Доведіть бульйон до кипіння у каструлі.',
                'Додайте сублімовані овочі та перемішайте.',
                'Всипте локшину і варіть 5–7 хвилин до готовності.',
                'Посоліть і поперчіть за смаком. Подавайте зі свіжою зеленню.',
            ],
        ],
        'en' => [
            'title'       => 'Quick Vegetable Soup with Freeze-dried Vegetables',
            'excerpt'     => 'A hearty soup with long-life ingredients — perfect for camping and busy weekdays.',
            'content'     => '<p>Freeze-dried vegetables rehydrate in hot water within minutes, retaining the taste and nutrition of fresh produce. The soup turns out rich and fragrant with minimal cooking time.</p>',
            'ingredients' => [
                '1 litre chicken or vegetable stock',
                '2 tbsp freeze-dried carrots',
                '2 tbsp freeze-dried peas',
                '1 tbsp freeze-dried spinach',
                '50 g small pasta or vermicelli',
                'Salt, pepper and fresh herbs to taste',
            ],
            'steps' => [
                'Bring the stock to a boil in a saucepan.',
                'Add the freeze-dried vegetables and stir.',
                'Add pasta and cook for 5–7 minutes until tender.',
                'Season with salt and pepper. Serve with fresh herbs.',
            ],
        ],
        'pt' => [
            'title'       => 'Sopa de Legumes Rápida com Legumes Liofilizados',
            'excerpt'     => 'Uma sopa substancial com ingredientes de longa duração — perfeita para campismo e dias de semana agitados.',
            'content'     => '<p>Os legumes liofilizados reidratam em água quente em apenas alguns minutos, preservando o sabor e os nutrientes dos legumes frescos. A sopa fica rica e aromática com um tempo de preparação mínimo.</p>',
            'ingredients' => [
                '1 litro de caldo de frango ou legumes',
                '2 c. sopa de cenoura liofilizada',
                '2 c. sopa de ervilhas liofilizadas',
                '1 c. sopa de espinafre liofilizado',
                '50 g de massa pequena ou aletria',
                'Sal, pimenta e ervas frescas a gosto',
            ],
            'steps' => [
                'Leve o caldo a ferver numa panela.',
                'Adicione os legumes liofilizados e mexa.',
                'Adicione a massa e cozinhe 5–7 minutos até ficar tenra.',
                'Tempere com sal e pimenta. Sirva com ervas frescas.',
            ],
        ],
        'prep_time' => '15 хв / 15 min',
        'servings'  => '4',
        'products'  => [28, 25, 29], // carrots, peas, spinach
    ],
];

/* ══════════════════════════════════════════════════════════════════
 * Delete Hello World post
 * ═══════════════════════════════════════════════════════════════ */
$hw = get_page_by_title( 'Hello world!', OBJECT, 'post' );
if ( $hw ) {
    wp_delete_post( $hw->ID, true );
    echo "Deleted: Hello world! (ID {$hw->ID})\n";
}

/* ══════════════════════════════════════════════════════════════════
 * Insert recipes
 * ═══════════════════════════════════════════════════════════════ */
$date_base = strtotime( '2026-05-01' );

foreach ( $recipes as $ri => $recipe ) {
    $date = date( 'Y-m-d H:i:s', $date_base + $ri * 7 * DAY_IN_SECONDS );
    $ids  = [];

    foreach ( [ 'ua', 'en', 'pt' ] as $lang ) {
        $data = $recipe[ $lang ];

        $post_id = wp_insert_post( [
            'post_type'     => 'post',
            'post_status'   => 'publish',
            'post_title'    => $data['title'],
            'post_excerpt'  => $data['excerpt'],
            'post_content'  => $data['content'],
            'post_date'     => $date,
            'post_date_gmt' => get_gmt_from_date( $date ),
        ] );

        if ( is_wp_error( $post_id ) ) {
            echo "ERROR inserting [{$lang}] {$data['title']}: " . $post_id->get_error_message() . "\n";
            continue;
        }

        // Recipe meta
        update_post_meta( $post_id, '_recipe_prep_time',   $recipe['prep_time'] );
        update_post_meta( $post_id, '_recipe_servings',    $recipe['servings'] );
        update_post_meta( $post_id, '_recipe_ingredients', $data['ingredients'] );
        update_post_meta( $post_id, '_recipe_steps',       $data['steps'] );
        update_post_meta( $post_id, '_recipe_products',    $recipe['products'] );

        // Assign Polylang language
        pll_set_post_language( $post_id, $lang );

        $ids[ $lang ] = $post_id;
        echo "Created [{$lang}] \"{$data['title']}\" (ID {$post_id})\n";
    }

    // Link all three as Polylang translations of each other
    if ( count( $ids ) === 3 ) {
        pll_save_post_translations( $ids );
        echo "Linked translations: " . implode( ', ', array_map(
            fn( $l, $id ) => "{$l}={$id}",
            array_keys( $ids ), array_values( $ids )
        ) ) . "\n\n";
    }
}

echo "Done.\n";
