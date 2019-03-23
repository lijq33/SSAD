<?php

namespace App\Http\Controllers;

use App\Facades\Map;

class MapsController extends Controller
{

    /**
     * @param $postalCode
     *
     * @return string
     */
    public function getAddress($postalCode)
    {
        $response = Map::findLocation($postalCode);
        return response()->json($response);
    }

    /**
     * @param $query
     *
     * @return string
     */
    public function autocomplete($query)
    {
        $response = Map::autocomplete($query);
        return response()->json($response);
    }

    /**
     * @param $geocode
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function geocode($geocode)
    {
        $response = Map::geocode($geocode);

        // Return first result only
        if (count($response) > 0) {
            $response = $response[0];
        }
        return response()->json($response);
    }
}
