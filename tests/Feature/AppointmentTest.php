<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\User;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
USE Illuminate\Foundation\Testing\DatabaseMigrations;

class AppointmentTest extends TestCase
{
    use DatabaseMigrations;
    
    
    /** @test*/
    public function it_can_get_appointment_after_login()
    {
        $response = $this->post('/api/auth/register', [
            'nric' => 'S1234567Z',
            'email' => 'test@test.com',
            'name' => 'testing name',
            'password' => '123456',
            'password_confirmation' => '123456',
            'telephone_number' => '12345678'
        ]);
        
        $response->assertStatus(200);

        $this->assertDatabaseHas('users', [
            'nric' => 'S1234567Z',
        ]);

        $login = $this->post('/api/auth/login',[
            'nric' => 'S1234567Z',
            'password' => '123456',
        ]);

        $login->assertStatus(200);
        
        $login->assertJson([
            'token_type' => 'bearer',
        ]);

        $token = $login->Json(['access_token']);


        $response =$this->get('/api/appointment/get', ['HTTP_Authorization' => 'Bearer' . $token]);
        
        $response->assertStatus(200);
    }

    /** @test*/
    public function it_cannot_get_appointment_before_login()
    {
        $response =$this->get('/api/appointment/get');
        
        $response->assertStatus(401);
    }
}
