<?php
defined( 'ABSPATH' ) || exit;

class SM_Instagram_Feed {

    private const TRANSIENT_KEY = 'sm_instagram_photos';
    private const CACHE_SECONDS = 3600;

    public static function init() {
        add_action( 'wp_ajax_sm_refresh_instagram', [ __CLASS__, 'ajax_refresh' ] );
    }

    public static function get_photos( int $count = 8 ): array {
        $cached = get_transient( self::TRANSIENT_KEY );
        if ( is_array( $cached ) ) {
            return array_slice( $cached, 0, $count );
        }

        $token = get_option( 'sm_instagram_token', '' );
        if ( ! $token ) return [];

        $url = add_query_arg( [
            'fields'       => 'id,media_type,media_url,thumbnail_url,permalink',
            'limit'        => 20,
            'access_token' => $token,
        ], 'https://graph.instagram.com/me/media' );

        $response = wp_remote_get( $url, [ 'timeout' => 10 ] );
        if ( is_wp_error( $response ) || wp_remote_retrieve_response_code( $response ) !== 200 ) {
            return [];
        }

        $body = json_decode( wp_remote_retrieve_body( $response ), true );
        if ( empty( $body['data'] ) ) return [];

        $photos = [];
        foreach ( $body['data'] as $item ) {
            if ( ! in_array( $item['media_type'], [ 'IMAGE', 'CAROUSEL_ALBUM' ], true ) ) continue;
            $photos[] = [
                'thumbnail' => $item['media_url'] ?? $item['thumbnail_url'] ?? '',
                'url'       => $item['permalink'] ?? '#',
            ];
        }

        set_transient( self::TRANSIENT_KEY, $photos, self::CACHE_SECONDS );
        return array_slice( $photos, 0, $count );
    }

    public static function ajax_refresh() {
        if ( ! current_user_can( 'manage_options' ) ) wp_die( -1 );
        delete_transient( self::TRANSIENT_KEY );
        wp_send_json_success( [ 'cleared' => true ] );
    }
}
