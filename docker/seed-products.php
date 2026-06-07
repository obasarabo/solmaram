<?php
$veg_id   = wp_create_category( 'Freeze-dried Vegetables' );
$fruit_id = wp_create_category( 'Freeze-dried Fruits & Berries' );

$best_tag   = wp_create_tag( 'best-seller' );
$best_tag_id = (int) $best_tag['term_id'];

$products = [
    [ 'name' => 'Freeze-dried Peas',         'price' => '189', 'cat' => $veg_id,   'weight' => '0.10', 'best' => true,  'desc' => 'Crispy freeze-dried green peas. Perfect for soups, salads, and snacking.' ],
    [ 'name' => 'Freeze-dried Sweet Corn',   'price' => '199', 'cat' => $veg_id,   'weight' => '0.10', 'best' => true,  'desc' => 'Sweet corn kernels freeze-dried to lock in natural flavour and nutrients.' ],
    [ 'name' => 'Freeze-dried Broccoli',     'price' => '219', 'cat' => $veg_id,   'weight' => '0.09', 'best' => false, 'desc' => 'Nutrient-rich broccoli florets, freeze-dried for maximum shelf life.' ],
    [ 'name' => 'Freeze-dried Carrots',      'price' => '179', 'cat' => $veg_id,   'weight' => '0.10', 'best' => false, 'desc' => 'Crunchy freeze-dried carrot slices, ideal for cooking or snacking.' ],
    [ 'name' => 'Freeze-dried Spinach',      'price' => '239', 'cat' => $veg_id,   'weight' => '0.08', 'best' => false, 'desc' => 'Baby spinach leaves freeze-dried at peak freshness.' ],
    [ 'name' => 'Freeze-dried Strawberries', 'price' => '269', 'cat' => $fruit_id, 'weight' => '0.08', 'best' => true,  'desc' => 'Bright red strawberries with intense flavour, freeze-dried and ready to eat.' ],
    [ 'name' => 'Freeze-dried Blueberries',  'price' => '299', 'cat' => $fruit_id, 'weight' => '0.08', 'best' => true,  'desc' => 'Wild blueberries packed with antioxidants, gently freeze-dried.' ],
    [ 'name' => 'Freeze-dried Raspberries',  'price' => '289', 'cat' => $fruit_id, 'weight' => '0.07', 'best' => false, 'desc' => 'Tangy freeze-dried raspberries — great in yogurt, porridge, or baking.' ],
    [ 'name' => 'Freeze-dried Apple Slices', 'price' => '209', 'cat' => $fruit_id, 'weight' => '0.10', 'best' => false, 'desc' => 'Crispy apple slices with no added sugar, naturally sweet.' ],
    [ 'name' => 'Freeze-dried Banana Chips', 'price' => '189', 'cat' => $fruit_id, 'weight' => '0.10', 'best' => false, 'desc' => 'Light and crunchy freeze-dried banana slices, a healthy snack.' ],
];

foreach ( $products as $p ) {
    $post_id = wp_insert_post( [
        'post_title'   => $p['name'],
        'post_content' => $p['desc'],
        'post_status'  => 'publish',
        'post_type'    => 'product',
    ] );

    wp_set_post_terms( $post_id, [ (int) $p['cat'] ], 'product_cat' );

    if ( $p['best'] ) {
        wp_set_post_terms( $post_id, [ $best_tag_id ], 'product_tag' );
    }

    update_post_meta( $post_id, '_price',         $p['price'] );
    update_post_meta( $post_id, '_regular_price', $p['price'] );
    update_post_meta( $post_id, '_weight',        $p['weight'] );
    update_post_meta( $post_id, '_manage_stock',  'no' );
    update_post_meta( $post_id, '_stock_status',  'instock' );
    update_post_meta( $post_id, '_visibility',    'visible' );
    update_post_meta( $post_id, '_virtual',       'no' );
    wp_set_object_terms( $post_id, 'simple', 'product_type' );

    wc_delete_product_transients( $post_id );

    WP_CLI::success( 'Created: ' . $p['name'] . ' (ID ' . $post_id . ')' );
}
