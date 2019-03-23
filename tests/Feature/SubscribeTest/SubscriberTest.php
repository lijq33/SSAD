<?php

namespace Tests\Feature\SubscribeTest;

use Tests\TestCase;
USE Illuminate\Foundation\Testing\DatabaseMigrations;

use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class SubscriberTest extends TestCase
{
    use DatabaseMigrations;

    /** @test */
    public function it_can_subscribe_to_sms()
    {
        $response = $this->post('/api/auth/subscriber', [
            'email' => 'test@test.com',
            'name' => 'testing name',
            'telephone_number' => '96325376',
        ]);

        $response->assertStatus(200);
        $response->assertSuccessful();

        $this->assertDatabaseHas('subscribers', [
            'telephone_number' => '96325376',
        ]);
    }

    /** @test */
    public function it_cannot_subscribe_to_sms_with_the_same_number()
    {
        $response = $this->post('/api/auth/subscriber', [
            'email' => 'test1@test.com',
            'name' => 'testing name',
            'telephone_number' => '96325376',
        ]);

        $response->assertStatus(200);
        $response->assertSuccessful();

        $this->assertDatabaseHas('subscribers', [
            'telephone_number' => '96325376',
        ]);

        $response = $this->post('/api/auth/subscriber', [
            'email' => 'test2@test.com',
            'name' => 'testing name',
            'telephone_number' => '96325376',
        ]);

        $response->assertStatus(422);
    }

    /** @test */
    public function it_cannot_subscribe_to_sms_with_the_same_email()
    {
        $response = $this->post('/api/auth/subscriber', [
            'email' => 'test@test.com',
            'name' => 'testing name',
            'telephone_number' => '96325376',
        ]);

        $response->assertStatus(200);
        $response->assertSuccessful();

        $this->assertDatabaseHas('subscribers', [
            'telephone_number' => '96325376',
        ]);

        $response = $this->post('/api/auth/subscriber', [
            'email' => 'test@test.com',
            'name' => 'testing name',
            'telephone_number' => '98765431',
        ]);

        $response->assertStatus(422);
    }

    /** @test */
    public function it_can_unsubscribe_to_sms()
    {
        $this->it_can_subscribe_to_sms();

        $response = $this->post('/api/auth/subscriber/delete', [
            'email' => 'test@test.com',
            'name' => 'testing name',
            'telephone_number' => '96325376',
        ]);

        $response->assertStatus(200);
        $response->assertSuccessful();

        $this->assertDatabaseMissing('subscribers', [
            'telephone_number' => '96325376',
        ]);
    }
    /** @test */
    public function it_cannot_unsubscribe_to_sms_when_not_subscribed()
    {
        $response = $this->post('/api/auth/subscriber/delete', [
            'email' => 'test@test.com',
            'name' => 'testing name',
            'telephone_number' => '12341234',
        ]);

        $response->assertStatus(401);
    }
}