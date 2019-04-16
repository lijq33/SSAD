<?php 
/* Author: Li JinQuan */
namespace App\Services;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\ClientException;

/**
 * Class MapService
 *
 * @package App\Services
 */
class MapService
{
    /**
     *  API Key for Google Services.
     */
    const GOOGLE_API_KEY = 'AIzaSyBYky3Q0f3bK8kZiN6LgvE97Np2Az1jdUg';

    /**
     * @param $query
     *
     * @return mixed
     */
    public function autocomplete($query)
    {
        $base   = "https://maps.googleapis.com/maps/api/";
        $key    = self::GOOGLE_API_KEY;
        $client = new Client(['base_url' => $base]);

        $param = [
            'key'        => $key,
            'components' => 'country:sg',
            'input'      => $query,
        ];

        $request  = $client->get('place/autocomplete/json', ['query' => $param]);
        $response = json_decode($request->getBody());

//        error_log(print_r("response Predictions", true));
//        error_log(print_r($response->predictions, true));
        $predictions = $this->parseAutoCompletePredictions($response->predictions);

        return $predictions;
    }

    /**
     * @param $predictions
     *
     * @return array
     */
    private function parseAutoCompletePredictions($predictions)
    {
        $results = [];
        foreach ($predictions as $prediction) {
            $results[] = $this->parseAutoCompletePrediction($prediction);
        }

        return $results;
    }

    /**
     * @param $response
     *
     * @return array
     */
    private function parseAutoCompletePrediction($response)
    {
        return [
            'value' => $response->description,
        ];
    }

    /**
     * @param $geocode
     *
     * @return mixed
     */
    public function geocode($geocode)
    {
        $base   = "https://maps.googleapis.com/maps/api/";
        $key    = self::GOOGLE_API_KEY;
        $client = new Client(['base_url' => $base]);

        $param = [
            'key'        => $key,
            'components' => 'country:sg',
            'address'    => $geocode,
        ];

        $request  = $client->get('geocode/json', ['query' => $param]);
        $response = json_decode($request->getBody());
        $response = $this->parseGeocode($response);

        return $response;
    }

    /**
     * @param $response
     *
     * @return array
     */
    private function parseGeocode($response)
    {
        $results = [];
        if (count($response->results) > 0) {
            foreach ($response->results as $result) {
                $results[] = [
                    'long_name' => $result->formatted_address,
                    'lat'       => $result->geometry->location->lat,
                    'lng'       => $result->geometry->location->lng,
                ];
            }
        }

        return $results;
    }

    /**
     * Find Location
     *
     * @param $query
     *
     * @return mixed
     *
     */
    public function findLocation($query)
    {

        $base   = "http://gothere.sg";
        $client = new Client(['base_uri' => $base]);

        $param = [
            'callback' => 1234,
            'output'   => 'json',
            'q'        => $query,
            'client'   => '',
            'sensor'   => false,
        ];

        $url = "http://gothere.sg/maps/geo?callback=1234&output=json&q=$query&client=&sensor=false";
        try {
            $request = $client->get($url);
            //$request = $client->get("/maps/geo", ['query' => $param]);
        } catch (ClientException $e) {
            dd($e);
        }
        //$response = json_decode($request->getBody());
        $response = (string) $request->getBody();
        $response = $this->parseJqueryString($response);

        $statusCode = $response->Status->code;

        switch ($statusCode) {
            case 603:
                return null;
                break;
            case 200:
                $response = $this->parseGoThereResponse($response);

                return $response;
        }

        return false;
        // 603 - no result found
        // 200 means ok!
        //return $response->results[0]->geometry->location;
    }

    /**
     * @param $response
     *
     * @return array
     */
    private function parseGoThereResponse($response)
    {
        $addressLine = (isset($response->Placemark[0]->AddressDetails->Country->AddressLine))
            ? $response->Placemark[0]->AddressDetails->Country->AddressLine : '';

        return [
            'full_address' => $response->Placemark[0]->address,
            'address_line' => $addressLine,
            'address'      => $response->Placemark[0]->AddressDetails->Country->Thoroughfare->ThoroughfareName,
            'postal_code'  => $response->Placemark[0]->AddressDetails->Country->Thoroughfare->PostalCode->PostalCodeNumber,
            'lat'          => $response->Placemark[0]->Point->coordinates[1],
            'lng'          => $response->Placemark[0]->Point->coordinates[0],
        ];
    }

    /**
     * @param $response
     *
     * @return mixed|string
     */
    private function parseJqueryString($response)
    {
        $response = str_replace('1234 && 1234', '', $response);
        $response = substr($response, 1);
        $response = substr($response, 0, strlen($response) - 1);
        $response = json_decode($response);

        return $response;
    }
}
