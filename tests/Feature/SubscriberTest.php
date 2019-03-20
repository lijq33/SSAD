<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\User;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
USE Illuminate\Foundation\Testing\DatabaseMigrations;

class UserTest extends TestCase
{
    use DatabaseMigrations;

    /** @test */
    public function it_can_subscribe_to_sms()
    {
        $response = $this->post('/api/auth/subscriber', [
            'email' => 'test@test.com',
            'name' => 'testing name',
            'telephone_number' => '98765432',
        ]);

        $response->assertStatus(200);
        $response->assertSuccessful();

        $this->assertDatabaseHas('subscribers', [
            'telephone_number' => '98765432',
        ]);
    }

    /** @test */
    public function it_cannot_subscribe_to_sms_with_the_same_number()
    {
        $response = $this->post('/api/auth/subscriber', [
            'email' => 'test1@test.com',
            'name' => 'testing name',
            'telephone_number' => '98765432',
        ]);

        $response->assertStatus(200);
        $response->assertSuccessful();

        $this->assertDatabaseHas('subscribers', [
            'telephone_number' => '98765432',
        ]);

        $response = $this->post('/api/auth/subscriber', [
            'email' => 'test1@test.com',
            'name' => 'testing name',
            'telephone_number' => '98765432',
        ]);

        $response->assertStatus(422);
    }

        /** @test */
        public function it_can_unsubscribe_to_sms()
        {
            
        }
}