<?php
defined( 'ABSPATH' ) || exit;

class SM_Nova_Poshta_API {

    private const API_URL = 'https://api.novaposhta.ua/v2.0/json/';
    private string $api_key;

    public function __construct( string $api_key ) {
        $this->api_key = $api_key;
    }

    private function request( string $model, string $method, array $properties = [] ): array {
        $response = wp_remote_post( self::API_URL, [
            'timeout' => 10,
            'headers' => [ 'Content-Type' => 'application/json' ],
            'body'    => wp_json_encode( [
                'apiKey'           => $this->api_key,
                'modelName'        => $model,
                'calledMethod'     => $method,
                'methodProperties' => (object) $properties,
            ] ),
        ] );

        if ( is_wp_error( $response ) ) return [];

        $body = json_decode( wp_remote_retrieve_body( $response ), true );
        return ( $body['success'] ?? false ) ? ( $body['data'] ?? [] ) : [];
    }

    public function get_cities( string $name ): array {
        $data = $this->request( 'Address', 'searchSettlements', [
            'CityName' => $name,
            'Limit'    => 15,
        ] );

        $cities = [];
        foreach ( $data[0]['Addresses'] ?? [] as $item ) {
            $cities[] = [
                'ref'   => $item['Ref'],
                'label' => $item['Present'],
            ];
        }
        return $cities;
    }

    public function get_warehouses( string $city_ref ): array {
        $data = $this->request( 'AddressGeneral', 'getWarehouses', [
            'SettlementRef' => $city_ref,
            'Limit'         => 200,
        ] );

        return array_map( fn( $w ) => [
            'ref'   => $w['Ref'],
            'label' => $w['Description'],
        ], $data );
    }

    public function calculate_shipping( array $params ): float {
        $data = $this->request( 'InternetDocument', 'getDocumentPrice', [
            'CitySender'    => $params['city_sender_ref'] ?? '',
            'CityRecipient' => $params['city_recipient_ref'] ?? '',
            'Weight'        => $params['weight'] ?? 1,
            'ServiceType'   => $params['service_type'] ?? 'WarehouseWarehouse',
            'Cost'          => $params['declared_cost'] ?? 500,
            'CargoType'     => 'Parcel',
            'SeatsAmount'   => 1,
        ] );

        return (float) ( $data[0]['Cost'] ?? 0 );
    }
}
