<?php
/**
 * Assign sm_use_case terms to products so the shop "Use Case" filter returns
 * results. Mapping is by product category. Idempotent — safe to re-run.
 *
 * Run: wp eval-file /var/www/html/seed-use-cases.php --allow-root
 */

// product_cat slug => sm_use_case slugs to assign
$map = [
    'freeze-dried-fruits-berries' => [ 'snack', 'cooking' ],
    'freeze-dried-vegetables'     => [ 'cooking', 'ready-meal' ],
];

$products = get_posts( [
    'post_type'      => 'product',
    'post_status'    => 'publish',
    'posts_per_page' => -1,
    'fields'         => 'ids',
] );

$total = 0;
foreach ( $products as $pid ) {
    $cats = wp_get_object_terms( $pid, 'product_cat', [ 'fields' => 'slugs' ] );
    $use_cases = [];
    foreach ( $cats as $cat ) {
        if ( isset( $map[ $cat ] ) ) {
            $use_cases = array_merge( $use_cases, $map[ $cat ] );
        }
    }
    $use_cases = array_values( array_unique( $use_cases ) );
    if ( $use_cases ) {
        wp_set_object_terms( $pid, $use_cases, 'sm_use_case', false );
        $total++;
        echo "product {$pid}: " . implode( ', ', $use_cases ) . " (cats: " . implode( ',', $cats ) . ")\n";
    }
}

// Refresh term counts
foreach ( [ 'snack', 'cooking', 'ready-meal' ] as $slug ) {
    $t = get_term_by( 'slug', $slug, 'sm_use_case' );
    if ( $t ) {
        wp_update_term_count_now( [ $t->term_id ], 'sm_use_case' );
        $t = get_term_by( 'slug', $slug, 'sm_use_case' );
        echo "term {$slug}: count={$t->count}\n";
    }
}

echo "\nDone. {$total} products tagged with use cases.\n";
