<?php

namespace Tests\Feature\CrisisTest;

use Tests\TestCase;
use App\User;
use App\Crisis;
USE Illuminate\Foundation\Testing\DatabaseMigrations;
use App\Events\CrisisUpdated;
use Illuminate\Support\Facades\Event;

use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithoutMiddleware;


class UpdateCrisisTest extends TestCase
{
    use DatabaseMigrations;

    public $password = '12345!qW';

    
    public function setUp()
    {
        parent::setUp();

        Event::fake();
    }

    
    /** @test */
    public function it_can_dispatch_an_CrisisUpdated_event_when_crisis_get_updated()
    {
        $this->it_can_update_a_new_crisis_as_CrisisManager();
        Event::assertDispatched(CrisisUpdated::class);
    
    }

    /** @test */
    public function it_can_update_a_new_crisis_as_CrisisManager()
    {
        $user = factory(User::class)
            ->states('CrisisManager')
            ->create();

        $crisis = factory(Crisis::class)
            ->create();

        $login = $this->post('/api/auth/login',[
            'nric' => $user->nric,
            'password' => $this->password,
        ]);

        $login->assertStatus(200);

        $response =$this->post('/api/crisis/'.$crisis->id, [
            'status' => 'resolved',
            'description' => 'updated description',
        ]);


        $response->assertStatus(200);

        $this->assertDatabaseHas('crises', [
            'id' => '1',
            'status' => 'resolved',
            'description' => 'updated description',
        ]);
    }

    /** @test */
    public function it_cannot_update_a_new_crisis_as_other_roles()
    {
        $crisis = factory(Crisis::class)
            ->create();
        $roles = ['CallCenterOperator', 'AccountManager'];

        foreach($roles as $role){
            $user = factory(User::class)
                ->states($role)
                ->create();

            $login = $this->post('/api/auth/login',[
                'nric' => $user->nric,
                'password' => $this->password,
            ]);

            $login->assertStatus(200);

            $response =$this->post('/api/crisis/'.$crisis->id, [
                'status' => 'resolved',
                'description' => 'updated description',
            ]);

            $response->assertStatus(401);

            $this->assertDatabaseMissing('crises', [
                'id' => '1',
                'status' => 'resolved',
                'description' => 'updated description',
            ]);

            $this->post('/api/auth/logout');
        }

    }
}