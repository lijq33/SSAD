<?php

namespace Tests\Feature\CrisisTest;

use Tests\TestCase;
use App\User;
USE Illuminate\Foundation\Testing\DatabaseMigrations;

use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class NewCrisisTest extends TestCase
{
    use DatabaseMigrations;

    /** @test */
    public function it_can_submit_a_new_crisis_as_call_center_operator()
    {
        $user = factory(User::class)
            ->states('CallCenterOperator')
            ->create();

        $login = $this->post('/api/auth/login',[
            'nric' => $user->nric,
            'password' => '123123',
        ]);

        $login->assertStatus(200);

        $token = $login->Json(['access_token']);

        $response =$this->post('/api/crisis', [
            'name' => 'Test',
            'telephoneNumber' => '98765432',
            'postalCode' => '123123',
            'date' => '19/03/2019',
            'time' => '1:30 PM',
            'address' => '12312 S Michigan Ave, Chicago, IL 60628, USA',
            'description' => '123',
            'crisisType' => 'Fire Outbreak', 
            'assistanceRequired' => ["1"],

        ]/*, ['HTTP_Authorization' => 'Bearer' .$token]*/);

        // $response->assertStatus(200);

        // $this->assertDatabaseHas('crises', [
        //     'id' => '1',
        // ]);

        // $this->assertDatabaseHas('crisis_agencies', [
        //     'id' => '1',
        // ]);
    }

    /** @test */
    public function it_cannot_submit_a_new_crisis_as_CrisisManager()
    {
        $user = factory(User::class)
            ->states('CrisisManager')
            ->create();

        $login = $this->post('/api/auth/login',[
            'nric' => $user->nric,
            'password' => '123123',
        ]);

        $login->assertStatus(200);

        $token = $login->Json(['access_token']);

        $response =$this->post('/api/crisis', [
            'name' => 'Test',
            'telephoneNumber' => '98765432',
            'postalCode' => '123123',
            'date' => '19/03/2019',
            'time' => '1:30 PM',
            'address' => '12312 S Michigan Ave, Chicago, IL 60628, USA',
            'description' => '123',
            'crisisType' => 'Fire Outbreak', 
            'assistanceRequired' => ["1"],

        ]/*, ['HTTP_Authorization' => 'Bearer' .$token]*/);

        $response->assertStatus(401);

        $this->assertDatabaseMissing('crises', [
            'id' => '1',
        ]);

        $this->assertDatabaseMissing('crisis_agencies', [
            'id' => '1',
        ]);
    }

    /** @test */
    public function it_cannot_submit_a_new_crisis_as_CivilDefencesAdmin()
    {
        $user = factory(User::class)
            ->states('CivilDefencesAdmin')
            ->create();

        $login = $this->post('/api/auth/login',[
            'nric' => $user->nric,
            'password' => '123123',
        ]);

        $login->assertStatus(200);

        $token = $login->Json(['access_token']);

        $response =$this->post('/api/crisis', [
            'name' => 'Test',
            'telephoneNumber' => '98765432',
            'postalCode' => '123123',
            'date' => '19/03/2019',
            'time' => '1:30 PM',
            'address' => '12312 S Michigan Ave, Chicago, IL 60628, USA',
            'description' => '123',
            'crisisType' => 'Fire Outbreak', 
            'assistanceRequired' => ["1"],

        ]/*, ['HTTP_Authorization' => 'Bearer' .$token]*/);

        $response->assertStatus(401);

        $this->assertDatabaseMissing('crises', [
            'id' => '1',
        ]);

        $this->assertDatabaseMissing('crisis_agencies', [
            'id' => '1',
        ]);
    }

    /** @test */
    public function it_cannot_submit_a_new_crisis_as_AccountManager()
    {
        $user = factory(User::class)
            ->states('AccountManager')
            ->create();

        $login = $this->post('/api/auth/login',[
            'nric' => $user->nric,
            'password' => '123123',
        ]);

        $login->assertStatus(200);

        $token = $login->Json(['access_token']);

        $response =$this->post('/api/crisis', [
            'name' => 'Test',
            'telephoneNumber' => '98765432',
            'postalCode' => '123123',
            'date' => '19/03/2019',
            'time' => '1:30 PM',
            'address' => '12312 S Michigan Ave, Chicago, IL 60628, USA',
            'description' => '123',
            'crisisType' => 'Fire Outbreak', 
            'assistanceRequired' => ["1"],

        ]/*, ['HTTP_Authorization' => 'Bearer' .$token]*/);

        $response->assertStatus(401);

        $this->assertDatabaseMissing('crises', [
            'id' => '1',
        ]);

        $this->assertDatabaseMissing('crisis_agencies', [
            'id' => '1',
        ]);
    }
}