<?php

namespace Tests\Feature;

use App\Facades\Map;
use Tests\TestCase;

class MapServiceTest extends TestCase
{
    /** @test */
    public function it_can_get_coordinates_from_postal_code()
    {
        $postalCode = 380132;
        $data = Map::findLocation($postalCode);

        $this->assertCoordinates(1.31644, 103.886, $data);
    }

    /** @test */
    public function it_can_get_coordinates_from_number_and_street_name()
    {
        $streetName = '4 jurong';
        $data       = Map::findLocation($streetName);

        $this->assertCoordinates(1.34602, 103.724, $data);

        $streetName = '6 serangoon';
        $data       = Map::findLocation($streetName);

        $this->assertCoordinates(1.34594, 103.878, $data);
    }

    /** @test */
    public function it_cannot_get_coordinates_from_long_string()
    {
        $streetName = 'punggol mrt em in 2018';
        $data       = Map::findLocation($streetName);

        $this->assertNull($data);
    }

    /** @test */
    public function it_cannot_get_coordinates_from_station_name()
    {
        $streetName = 'punggol mrt';
        $data       = Map::findLocation($streetName);

        $this->assertCoordinates(1.4046099999999999, 103.902, $data);

        $streetName = 'aljunied mrt';
        $data       = Map::findLocation($streetName);

        $this->assertCoordinates(1.31661, 103.883, $data);
    }

    /** @test */
    public function it_can_get_coordinates_from_street_address()
    {
        $streetName = 'Geylang East Ave 1';
        $data       = Map::findLocation($streetName);

        $this->assertCoordinates(1.31591, 103.884, $data);
    }

    /** @test */
    public function it_can_get_coordinates_from_bool_tiong()
    {
        $streetName = 'boon tiong rd';
        $data       = Map::findLocation($streetName);

        $this->assertCoordinates(1.28708, 103.83, $data);
    }

    /**
     * Helper function for asserting coordinates.
     *
     * @param $lat
     * @param $lng
     * @param $data
     */
    private function assertCoordinates($lat, $lng, $data)
    {
        $this->assertEquals($lat, $data['lat']);
        $this->assertEquals($lng, $data['lng']);
    }
}
