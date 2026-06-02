<?php
defined( 'ABSPATH' ) || exit;

class SM_LiqPay_API {

    private string $public_key;
    private string $private_key;
    private const API_URL = 'https://www.liqpay.ua/api/';
    private const CHECKOUT_URL = 'https://www.liqpay.ua/api/3/checkout';

    public function __construct( string $public_key, string $private_key ) {
        $this->public_key  = $public_key;
        $this->private_key = $private_key;
    }

    public function encode( array $params ): string {
        $params['public_key'] = $this->public_key;
        return base64_encode( wp_json_encode( $params ) );
    }

    public function signature( string $data ): string {
        return base64_encode( sha1( $this->private_key . $data . $this->private_key, true ) );
    }

    public function get_checkout_url( array $params ): string {
        $data = $this->encode( $params );
        $sig  = $this->signature( $data );
        return add_query_arg( [ 'data' => $data, 'signature' => $sig ], self::CHECKOUT_URL );
    }

    public function decode_callback( string $data ): ?array {
        $json = base64_decode( $data );
        return $json ? json_decode( $json, true ) : null;
    }

    public function verify_signature( string $data, string $signature ): bool {
        return hash_equals( $this->signature( $data ), $signature );
    }
}
