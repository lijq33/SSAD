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

    public $password = '12345!qW';
       
    public function setUp()
    {
        parent::setUp();

        Event::fake();
    }


    /** @test */
    public function it_can_dispatch_an_CrisisCreated_event_when_there_is_a_new_crisis()
    {
        $this->it_can_submit_a_new_crisis_as_call_center_operator();

        Event::assertDispatched(CrisisCreated::class);
    
    }

    /** @test */
    public function it_can_submit_a_new_crisis_as_call_center_operator()
    {
        $user = factory(User::class)
            ->states('CallCenterOperator')
            ->create();

        $login = $this->post('/api/auth/login',[
            'nric' => $user->nric,
            'password' => $this->password,
        ]);

        $login->assertStatus(200);

        $response =$this->post('/api/crisis', [
            'name' => 'Test',
            'telephoneNumber' => '98765432',
            'postalCode' => '123123',
            'date' => '19/03/2019',
            'time' => '1:30 PM',
            'address' => '12312 S Michigan Ave, Chicago, IL 60628, USA',
            'description' => 'This is a short description about the crisis',
            'crisisType' => 'Fire Outbreak', 
            'assistanceRequired' => '1',
            'radius' => '0',
        ]);

        $response->assertStatus(200);

        $this->assertDatabaseHas('crises', [
            'id' => '1',
        ]);

        $this->assertDatabaseHas('crisis_agencies', [
            'id' => '1',
        ]);
    }

    /** @test */
    public function it_cannot_submit_a_new_crisis_as_CrisisManager()
    {
        $user = factory(User::class)
            ->states('CrisisManager')
            ->create();

        $login = $this->post('/api/auth/login',[
            'nric' => $user->nric,
            'password' => $this->password,
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
            'password' => $this->password,
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