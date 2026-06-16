<?php
/**
 * Seed customer reviews for the homepage carousel.
 * Each review carries _sm_review_lang meta so the carousel can filter
 * by current Polylang language. Run once per environment.
 *
 * Run: wp eval-file /var/www/html/seed-reviews.php --allow-root
 */

$reviews = [
    /* ── Ukrainian ─────────────────────────────────────────────── */
    [
        'lang'    => 'ua',
        'post_id' => 30, // Сублімована полуниця
        'author'  => 'Олена М.',
        'email'   => 'olena.m@example.com',
        'rating'  => 5,
        'date'    => '2026-04-12 10:22:00',
        'content' => 'Замовляю сублімовану полуницю вже третій раз. '
                   . 'Дитина їсть як снек — хрустка, солодка, без зайвого. '
                   . 'Дуже задоволена якістю та швидкою доставкою!',
    ],
    [
        'lang'    => 'ua',
        'post_id' => 31, // Сублімована чорниця
        'author'  => 'Андрій К.',
        'email'   => 'andrii.k@example.com',
        'rating'  => 5,
        'date'    => '2026-05-03 14:08:00',
        'content' => 'Брав чорницю та малину для туристичного походу. '
                   . 'Легка, компактна, зберігається ідеально. '
                   . 'Смак практично як у свіжих ягід — рекомендую всім туристам!',
    ],
    /* ── English ───────────────────────────────────────────────── */
    [
        'lang'    => 'en',
        'post_id' => 31, // Blueberries
        'author'  => 'Maria S.',
        'email'   => 'maria.s@example.com',
        'rating'  => 5,
        'date'    => '2026-04-20 09:15:00',
        'content' => 'Ordered the blueberries three times now — my kids love them '
                   . 'as an after-school snack. Crispy, naturally sweet, '
                   . 'no additives. Really impressed with the quality!',
    ],
    [
        'lang'    => 'en',
        'post_id' => 25, // Peas
        'author'  => 'James T.',
        'email'   => 'james.t@example.com',
        'rating'  => 5,
        'date'    => '2026-05-14 16:40:00',
        'content' => 'Bought the vegetable range for camping trips. '
                   . 'Lightweight, stores perfectly and rehydrates in minutes. '
                   . 'Incredibly fresh-tasting for freeze-dried. Will reorder.',
    ],
    /* ── Portuguese ────────────────────────────────────────────── */
    [
        'lang'    => 'pt',
        'post_id' => 30, // Strawberries
        'author'  => 'Sofia R.',
        'email'   => 'sofia.r@example.com',
        'rating'  => 5,
        'date'    => '2026-05-28 11:55:00',
        'content' => 'Encomendei os morangos liofilizados e fiquei impressionada '
                   . 'com a qualidade. Sabor natural, crocantes e sem conservantes. '
                   . 'Definitivamente voltarei a comprar!',
    ],
];

$product_counts = [];

foreach ( $reviews as $r ) {
    $id = wp_insert_comment( [
        'comment_post_ID'      => $r['post_id'],
        'comment_author'       => $r['author'],
        'comment_author_email' => $r['email'],
        'comment_content'      => $r['content'],
        'comment_type'         => 'review',
        'comment_approved'     => '1',
        'comment_date'         => $r['date'],
        'comment_date_gmt'     => get_gmt_from_date( $r['date'] ),
    ] );

    if ( $id ) {
        add_comment_meta( $id, 'rating',          $r['rating'], true );
        add_comment_meta( $id, '_sm_review_lang', $r['lang'],   true );
        $product_counts[ $r['post_id'] ][] = $r['rating'];
        echo "Added [{$r['lang']}] review by {$r['author']} on post {$r['post_id']} (comment #{$id})\n";
    } else {
        echo "ERROR: could not insert review by {$r['author']}\n";
    }
}

// Update WooCommerce review count + average rating per product
foreach ( $product_counts as $post_id => $ratings ) {
    $avg = array_sum( $ratings ) / count( $ratings );
    update_post_meta( $post_id, '_wc_review_count',   count( $ratings ) );
    update_post_meta( $post_id, '_wc_average_rating', number_format( $avg, 2 ) );
}

echo "\nDone. " . count( $reviews ) . " reviews inserted.\n";
