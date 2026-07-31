<?php
/**
 * Blog & Recipes removed (FR-08 dropped).
 *
 * The storefront has no editorial section: the posts page, post permalinks and
 * every post archive (category, tag, date, author) redirect to the home page,
 * and posts are excluded from site search. The `post` type itself stays
 * registered — WordPress core depends on it — but nothing routes to it.
 *
 * The theme's archive.php / single.php and SM_Recipe_Meta were deleted with
 * this change; see git history to restore them.
 */
defined( 'ABSPATH' ) || exit;

class SM_Disable_Blog {

    /** Slugs the Blog page used in each language, for old inbound links. */
    private const LEGACY_BLOG_SLUGS = [ 'blog', 'blogue', 'блог' ];

    public static function init(): void {
        add_action( 'template_redirect', [ __CLASS__, 'block_blog_routes' ] );
        add_action( 'pre_get_posts', [ __CLASS__, 'exclude_posts_from_search' ] );
    }

    /**
     * Send every blog route — and any leftover /blog/ style URL — to the home page.
     */
    public static function block_blog_routes(): void {
        if ( is_admin() || is_feed() ) {
            return;
        }

        $is_blog_route = is_home()
            || is_singular( 'post' )
            || is_category()
            || is_tag()
            || is_date()
            || is_author();

        if ( ! $is_blog_route && ! self::is_orphaned_blog_url() ) {
            return;
        }

        wp_safe_redirect( self::home_url(), 302 );
        exit;
    }

    /**
     * Keep posts out of search results — search.php only renders products now.
     */
    public static function exclude_posts_from_search( WP_Query $query ): void {
        if ( is_admin() || ! $query->is_main_query() || ! $query->is_search() ) {
            return;
        }

        $post_types = (array) ( $query->get( 'post_type' ) ?: [ 'product', 'page' ] );
        $query->set( 'post_type', array_values( array_diff( $post_types, [ 'post' ] ) ) );
    }

    /**
     * Blog URLs that now resolve to nothing.
     *
     * WordPress calls set_404() when a route matches no posts, and that resets
     * is_date()/is_category()/etc. to false — so an emptied archive can only be
     * recognised from the surviving query vars. Also covers the old Blog page
     * URLs (/blog/, /en/blog/, /pt/blogue/, /блог/), whose pages are deleted.
     */
    private static function is_orphaned_blog_url(): bool {
        if ( ! is_404() ) {
            return false;
        }

        // An archive route whose flags were cleared by set_404(). WP_Query seeds
        // 'm' and 'cat' with 0 on every request, so test for a non-empty value
        // rather than for the var being present.
        foreach ( [ 'year', 'monthnum', 'day', 'm', 'category_name', 'cat', 'tag', 'author_name' ] as $var ) {
            if ( ! empty( get_query_var( $var ) ) ) {
                return true;
            }
        }

        $path     = trim( (string) parse_url( $_SERVER['REQUEST_URI'] ?? '', PHP_URL_PATH ), '/' );
        $segments = array_filter( explode( '/', rawurldecode( $path ) ) );

        foreach ( $segments as $segment ) {
            if ( in_array( mb_strtolower( $segment ), self::LEGACY_BLOG_SLUGS, true ) ) {
                return true;
            }
        }

        return false;
    }

    private static function home_url(): string {
        return function_exists( 'pll_home_url' ) ? pll_home_url() : home_url( '/' );
    }
}
