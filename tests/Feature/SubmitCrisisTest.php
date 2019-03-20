<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\User;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
USE Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class UserTest extends TestCase
{
    use DatabaseMigrations;

    public $token;

    public function __construct(){
            
        //db create user
        DB::table('users')->insert([
            'name' => Str::random(10),
            'nric' => 'S1234569Z',
            'email' => Str::random(10).'@gmail.com',
            'password' => bcrypt('123123'),
            'telephone_number' => random_int(90000000 ,99999999),
            'roles' => 'CallCenterOperator'
        ]);

        //login
        $login = $this->post('/api/auth/login',[
            'nric' => 'S1234569Z',
            'password' => '123123',
        ]);


        $this->token = $login->Json(['access_token']);
    }

    /** @test */
    public function it_can_submit_a_new_crisis()
    {
     

        $response =$this->post('/api/crisis', [
            'name' => 'Test',
            'telephoneNumber' => '98765432',
            'date' => '19/03/2019',
            'time' => '11:00:00',
            'address' => '123',
            'postalCode' => '123123',
            'description' => '123',
            'assistanceRequired' => '',
            'crisisType' => '1', 
        ], ['HTTP_Authorization' => 'Bearer' . $this->token]);
        
        $response->assertStatus(200);
    }
}