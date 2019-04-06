<?php

namespace App\Http\Controllers;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\ClientException;

use Illuminate\Http\Request;

class APIController extends Controller
{
    /**
     * @param $query
     *
     * @return string
     */
    public function bombshelter()
    {

        // Create a client with a base URI
        $client = new Client(['base_uri' => 'https://data.gov.sg/api/']);
        $request = $client->request('GET', 'action/datastore_search?resource_id=4ee17930-4780-403b-b6d4-b963c7bb1c09');

        $response = json_decode($request->getBody());

        return response()->json([
            'bombshelter' => $response->result->records,
        ], 200);
    }
}
