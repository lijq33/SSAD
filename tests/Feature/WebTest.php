<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
USE Illuminate\Foundation\Testing\DatabaseMigrations;

class WebTest extends TestCase
{
    use DatabaseMigrations;

    /** @test*/
    public function it_can_visit_home_page()
    {
        $response = $this->get('/');

        $response->assertStatus(200);
    }

    /** @test*/
    public function it_can_visit_login_page()
    {
        $response = $this->get('/login');

        $response->assertStatus(200);
    }

    /** @test*/
    public function it_can_visit_healthservices_page()
    {
        $response = $this->get('/Healthcare/Hospital');

        $response->assertStatus(200);

        $response = $this->get('/Healthcare/Polyclinic');

        $response->assertStatus(200);
  
        $response = $this->get('/Healthcare/Pharmacy');

        $response->assertStatus(200);

        $response = $this->get('/Healthcare/Dental');

        $response->assertStatus(200);

        $response = $this->get('/Healthcare/Chas%20Clinic');

        $response->assertStatus(200);
    }

    /** @test*/
    public function it_cannot_visit_feedback_page_before_login()
    {

        $response = $this->get('/feedback/show');

        $response->assertLocation('/');
    }



}
