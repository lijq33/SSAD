<?php

namespace Tests\Feature\CrisisTest;

use Tests\TestCase;
use App\User;
use App\Crisis;
USE Illuminate\Foundation\Testing\DatabaseMigrations;

use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class UpdateCrisisTest extends TestCase
{
    use DatabaseMigrations;
    
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
            'password' => '123123',
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
        $users = ['CallCenterOperator', 'AccountManager', 'CivilDefencesAdmin'];

        foreach($users as $userState){
            $user = factory(User::class)
                ->states($userState)
                ->create();

            $login = $this->post('/api/auth/login',[
                'nric' => $user->nric,
                'password' => '123123',
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
        }

    }
}