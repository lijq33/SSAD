<?php

namespace Tests\Feature\CrisisTest;

use Tests\TestCase;
use App\User;
use Illuminate\Foundation\Testing\DatabaseMigrations;

use App\Events\CrisisCreated;
use Illuminate\Support\Facades\Event;

use Illuminate\Foundation\Testing\WithFaker;

class NewCrisisTest extends TestCase
{
    use DatabaseMigrations, WithFaker;

    /** @test */
    public function it_can_report_a_new_crisis()
    {

        $response =$this->post('/api/report/crisis', [
            'name' => 'Test',
            'telephoneNumber' => '98765432',
            'postalCode' => '123123',
            'date' => '19/03/2019',
            'time' => '1:30 PM',
            'location' => '12312 S Michigan Ave, Chicago, IL 60628, USA',
            'description' => 'This is a short description about the crisis',
            'crisisType' => 'Fire Outbreak', 
            'radius' => '0',
        ]);

        $response->assertStatus(200);

        $this->assertDatabaseHas('report_crises', [
            'id' => '1',
        ]);
    }

    /** @test */
    public function it_cannot_report_a_new_crisis_with_missing_fields()
    {
        //description and image can be empty
        
        $response =$this->post('/api/report/crisis', [
            'name' => '',
            'telephoneNumber' => '98765432',
            'postalCode' => '123123',
            'date' => '19/03/2019',
            'time' => '1:30 PM',
            'location' => '12312 S Michigan Ave, Chicago, IL 60628, USA',
            'description' => 'This is a short description about the crisis',
            'crisisType' => 'Fire Outbreak', 
            'radius' => '0',
        ]);

        $response->assertStatus(422);

        $response =$this->post('/api/report/crisis', [
            'name' => 'Test',
            'telephoneNumber' => '',
            'postalCode' => '123123',
            'date' => '19/03/2019',
            'time' => '1:30 PM',
            'location' => '12312 S Michigan Ave, Chicago, IL 60628, USA',
            'description' => 'This is a short description about the crisis',
            'crisisType' => 'Fire Outbreak', 
            'radius' => '0',
        ]);

        $response->assertStatus(422);

        $response =$this->post('/api/report/crisis', [
            'name' => 'Test',
            'telephoneNumber' => '98765432',
            'postalCode' => '',
            'date' => '19/03/2019',
            'time' => '1:30 PM',
            'location' => '12312 S Michigan Ave, Chicago, IL 60628, USA',
            'description' => 'This is a short description about the crisis',
            'crisisType' => 'Fire Outbreak', 
            'radius' => '0',
        ]);

        $response->assertStatus(422);

        $response =$this->post('/api/report/crisis', [
            'name' => 'Test',
            'telephoneNumber' => '98765432',
            'postalCode' => '123123',
            'date' => '',
            'time' => '1:30 PM',
            'location' => '12312 S Michigan Ave, Chicago, IL 60628, USA',
            'description' => 'This is a short description about the crisis',
            'crisisType' => 'Fire Outbreak', 
            'radius' => '0',
        ]);

        $response->assertStatus(422);

        $response =$this->post('/api/report/crisis', [
            'name' => 'Test',
            'telephoneNumber' => '98765432',
            'postalCode' => '123123',
            'date' => '19/03/2019',
            'time' => '',
            'location' => '12312 S Michigan Ave, Chicago, IL 60628, USA',
            'description' => 'This is a short description about the crisis',
            'crisisType' => 'Fire Outbreak', 
            'radius' => '0',
        ]);

        $response->assertStatus(422);

        $response =$this->post('/api/report/crisis', [
            'name' => 'Test',
            'telephoneNumber' => '98765432',
            'postalCode' => '123123',
            'date' => '19/03/2019',
            'time' => '1:30 PM',
            'location' => '',
            'description' => 'This is a short description about the crisis',
            'crisisType' => 'Fire Outbreak', 
            'radius' => '0',
        ]);

        $response->assertStatus(422);

        $response =$this->post('/api/report/crisis', [
            'name' => 'Test',
            'telephoneNumber' => '98765432',
            'postalCode' => '123123',
            'date' => '19/03/2019',
            'time' => '1:30 PM',
            'location' => '12312 S Michigan Ave, Chicago, IL 60628, USA',
            'description' => 'This is a short description about the crisis',
            'crisisType' => '', 
            'radius' => '0',
        ]);

        $response->assertStatus(422);
    }
    
    /** @test */
    public function it_cannot_report_a_new_crisis_without_radius_if_crisis_is_dengue()
    {
        $response =$this->post('/api/report/crisis', [
            'name' => 'Test',
            'telephoneNumber' => '98765432',
            'postalCode' => '123123',
            'date' => '19/03/2019',
            'time' => '1:30 PM',
            'location' => '12312 S Michigan Ave, Chicago, IL 60628, USA',
            'description' => 'This is a short description about the crisis',
            'crisisType' => 'Dengue', 
            'radius' => '',
        ]);

        $response->assertStatus(422);
    }

      
    /** @test */
    public function it_can_report_a_new_crisis_without_radius_if_crisis_is_not_dengue()
    {
        $response =$this->post('/api/report/crisis', [
            'name' => 'Test',
            'telephoneNumber' => '98765432',
            'postalCode' => '123123',
            'date' => '19/03/2019',
            'time' => '1:30 PM',
            'location' => '12312 S Michigan Ave, Chicago, IL 60628, USA',
            'description' => 'This is a short description about the crisis',
            'crisisType' => 'Fire Outbreak', 
            'radius' => '',
        ]);

        $response->assertStatus(200);

        $response =$this->post('/api/report/crisis', [
            'name' => 'Test',
            'telephoneNumber' => '98765432',
            'postalCode' => '123123',
            'date' => '19/03/2019',
            'time' => '1:30 PM',
            'location' => '12312 S Michigan Ave, Chicago, IL 60628, USA',
            'description' => 'This is a short description about the crisis',
            'crisisType' => 'Gas Leak', 
            'radius' => '',
        ]);

        $response->assertStatus(200);
    }

}