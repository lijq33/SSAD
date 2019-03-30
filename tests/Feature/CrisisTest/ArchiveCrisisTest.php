<?php

namespace Tests\Feature\CrisisTest;

use Tests\TestCase;
use App\User;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Support\Facades\Event;

use App\Crisis;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithoutMiddleware;

class ArchiveCrisisTest extends TestCase
{
    use DatabaseMigrations, WithFaker;

    public $password = '12345!qW';

    public function setup()
    {
        parent::setUp();
        Event::fake();

        $user = factory(User::class)
            ->states('CallCenterOperator')
            ->create();

        $login = $this->post('/api/auth/login',[
            'nric' => $user->nric,
            'password' => $this->password,
        ]);

        $response = $this->post('/api/crisis', [
            'name' => 'Test',
            'telephoneNumber' => '98765432',
            'postalCode' => '123123',
            'date' => '19/03/2019',
            'time' => '1:30 PM',
            'address' => '12312 S Michigan Ave, Chicago, IL 60628, USA',
            'description' => 'This is a short description about the crisis4',
            'crisisType' => 'Fire Outbreak', 
            'assistanceRequired' => ["1"],
        ]);

        $response = $this->post('/api/auth/logout');

    }

    /** @test */
    public function it_cannot_archive_crisis_when_unauthenticated()
    {
        $response = $this->delete('/api/crisis/1');

        $response->assertStatus(401);
    }

    /** @test */
    public function it_can_archive_crisis_as_CrisisManager()
    {
        $user = factory(User::class)
            ->states('CrisisManager')
            ->create();

        $login = $this->post('/api/auth/login',[
            'nric' => $user->nric,
            'password' => $this->password,
        ]);

        $login->assertStatus(200);

        $response = $this->delete('/api/crisis/1');

        $response->assertStatus(200);
    }

    /** @test */
    public function it_cannot_archive_crisis_as_other_roles()
    {
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

            $response = $this->delete('/api/crisis/1');

            $this->assertDatabaseHas('crises', [
                'id' => '1',
            ]);
    
            $this->post('/api/auth/logout');
        }
    }
}