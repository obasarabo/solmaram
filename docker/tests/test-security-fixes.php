<?php
/**
 * Standalone unit tests for the security-fixes branch. No WordPress bootstrap —
 * stubs the one base class and exercises the pure logic directly.
 *
 *   #1  SM_CSV_Export::csv_cell()              — CSV formula-injection neutralizer
 *   #5  WC_SM_Monobank_Gateway::verify_body_signature() — ECDSA-SHA256 verification
 *
 * Run:  docker compose run --rm wpcli php /var/www/html/wp-content/test-security-fixes.php
 *       (set SM_PLUGINS to override the plugins dir; defaults to the container path)
 */
error_reporting( E_ALL & ~E_DEPRECATED );

$PLUGINS = getenv( 'SM_PLUGINS' ) ?: '/var/www/html/wp-content/plugins';
define( 'ABSPATH', __DIR__ . '/' ); // satisfy the `defined('ABSPATH') || exit;` guards

$pass = 0; $fail = 0;
function check( string $name, bool $cond ): void {
    global $pass, $fail;
    if ( $cond ) { $pass++; echo "  PASS  $name\n"; }
    else         { $fail++; echo "  FAIL  $name\n"; }
}

echo "== #5 Monobank ECDSA (X-Sign) verification ==\n";
class WC_Payment_Gateway {} // minimal stub so the gateway class can load
require "$PLUGINS/solmaram-liqpay/includes/class-wc-monobank-gateway.php";

// Generate a throwaway EC keypair (same curve family Monobank uses) and sign a
// sample webhook body exactly as Monobank would: ECDSA-SHA256, X-Sign = base64(sig).
$res = openssl_pkey_new( [ 'private_key_type' => OPENSSL_KEYTYPE_EC, 'curve_name' => 'prime256v1' ] );
openssl_pkey_export( $res, $priv_pem );
$pub_pem = openssl_pkey_get_details( $res )['key'];

$body = '{"invoiceId":"p2_test","status":"success","amount":50000,"ccy":980,"reference":"42"}';
openssl_sign( $body, $sig, $priv_pem, OPENSSL_ALGO_SHA256 );
$x_sign = base64_encode( $sig );

check( 'valid signature accepted',
    WC_SM_Monobank_Gateway::verify_body_signature( $body, base64_decode( $x_sign, true ), $pub_pem ) === true );
check( 'tampered body rejected',
    WC_SM_Monobank_Gateway::verify_body_signature( $body . ' ', $sig, $pub_pem ) === false );
$bad_sig = substr( $sig, 0, -1 ) . chr( ord( substr( $sig, -1 ) ) ^ 0x01 );
check( 'tampered signature rejected',
    WC_SM_Monobank_Gateway::verify_body_signature( $body, $bad_sig, $pub_pem ) === false );
$res2    = openssl_pkey_new( [ 'private_key_type' => OPENSSL_KEYTYPE_EC, 'curve_name' => 'prime256v1' ] );
$pub2    = openssl_pkey_get_details( $res2 )['key'];
check( 'wrong public key rejected',
    WC_SM_Monobank_Gateway::verify_body_signature( $body, $sig, $pub2 ) === false );
check( 'malformed PEM rejected (no crash)',
    WC_SM_Monobank_Gateway::verify_body_signature( $body, $sig, 'not-a-pem' ) === false );
check( 'empty signature rejected',
    WC_SM_Monobank_Gateway::verify_body_signature( $body, '', $pub_pem ) === false );

echo "== #1 CSV formula-injection neutralizer ==\n";
require "$PLUGINS/solmaram-core/includes/class-csv-export.php";
$m = new ReflectionMethod( 'SM_CSV_Export', 'csv_cell' );
$m->setAccessible( true );
$cell = fn( $v ) => $m->invoke( null, $v );

check( '=formula neutralized',              $cell( '=cmd|calc' )   === "'=cmd|calc" );
check( 'leading-space +space=space formula', $cell( ' =1+1' )      === "' =1+1" );      // Excel ignores leading space
check( '+ neutralized',                     $cell( '+1' )          === "'+1" );
check( '- neutralized',                     $cell( '-1' )          === "'-1" );
check( '@ neutralized',                     $cell( '@SUM(A1)' )    === "'@SUM(A1)" );
check( 'leading tab neutralized',           $cell( "\t=1" )        === "'\t=1" );
check( 'leading CR neutralized',            $cell( "\r=1" )        === "'\r=1" );
check( 'plain name untouched',              $cell( 'John Doe' )    === 'John Doe' );
check( 'email (@ not leading) untouched',   $cell( 'a@b.com' )     === 'a@b.com' );
check( 'inner hyphen untouched',            $cell( '12-34' )       === '12-34' );
check( 'integer coerced, untouched',        $cell( 42 )            === '42' );

echo "\nRESULT: $pass passed, $fail failed\n";
exit( $fail ? 1 : 0 );
