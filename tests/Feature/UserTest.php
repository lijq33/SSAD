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
  
    }

    /** @test */
    public function it_cannot_subscribe_to_sms_with_the_same_number()
    {

    }

    /** @test */
    public function it_cannot_register_user_account_if_unauthenticated()
    {

        $response = $this->post('/api/auth/register', [
            'nric' => 'S9111211Z',
            'email' => 'test@test.com',
            'name' => 'testing name',
            'password' => '123456',
            'password_confirmation' => '123456',
            'telephone_number' => '12245678',
        ]);
        
        $response->assertStatus(200);
        $response->assertSuccessful();

        $this->assertDatabaseHas('users', [
            'nric' => 'S9111211Z',
        ]);

    }
    
    /** @test */
    public function it_can_register_user_account_if_authenticated()
    {
        $nric = 'S1234561Z';
        $response = $this->post('/api/auth/register', [
            'nric' => $nric,
            'email' => '1@test.com',
            'name' => 'testing name',
            'password' => '123456',
            'password_confirmation' => '123456',
            'telephone_number' => '87654321',
        ]);
         
        $response->assertStatus(200);
        $this->assertDatabasehas('users', [
            'nric' => $nric,
        ]);

        $response = $this->post('/api/auth/register', [
            'nric' => $nric,
            'email' => '2@test.com',
            'name' => 'testing name',
            'password' => '123456',
            'password_confirmation' => '123456',
            'telephone_number' => '12345678',
        ]);
 
        $response->assertStatus(422);
    }

    /** @test */
    public function it_cannot_register_user_account_with_same_email()
    {
        $email = 'test@test.com';

        $response = $this->post('/api/auth/register', [
            'nric' => 'S1234567Z',
            'email' => $email,
            'name' => 'testing name',
            'password' => '123456',
            'password_confirmation' => '123456',
            'telephone_number' => '87654321',
        ]);
         
        $response->assertStatus(200);
        $this->assertDatabasehas('users', [
            'nric' => 'S1234567Z',
        ]);

        $response = $this->post('/api/auth/register', [
            'nric' => 'S1234568Z',
            'email' => $email,
            'name' => 'testing name',
            'password' => '123456',
            'password_confirmation' => '123456',
            'telephone_number' => '12345678',
        ]);
 
        $response->assertStatus(422);
    }

    /** @test */
    public function it_cannot_register_user_account_with_short_nric()
    {

        $response = $this->post('/api/auth/register', [
            'nric' => 'S123456Z',
            'email' => 'test@test.com',
            'name' => 'testing name',
            'password' => '123456',
            'password_confirmation' => '123456',
            'telephone_number' => '12245678',
        ]);
        
        $response->assertStatus(422);

        $this->assertDatabaseMissing('users', [
            'nric' => 'S123456Z',
        ]);
    }

    /** @test */
    public function it_cannot_register_user_account_with_long_nric()
    {

        $response = $this->post('/api/auth/register', [
            'nric' => 'S12345678Z',
            'email' => 'test@test.com',
            'name' => 'testing name',
            'password' => '123456',
            'password_confirmation' => '123456',
            'telephone_number' => '12245678',
        ]);
        
        $response->assertStatus(422);

        $this->assertDatabaseMissing('users', [
            'nric' => 'S12345678Z',
        ]);
    }

    /** @test */
    public function it_cannot_register_user_account_with_short_telephone()
    {

        $response = $this->post('/api/auth/register', [
            'nric' => 'S1234567Z',
            'email' => 'test@test.com',
            'name' => 'testing name',
            'password' => '123456',
            'password_confirmation' => '123456',
            'telephone_number' => '1234567',
        ]);
        
        $response->assertStatus(422);

        $this->assertDatabaseMissing('users', [
            'nric' => 'S1234567Z',
        ]);
    }

    /** @test */
    public function it_cannot_register_user_account_with_long_telephone()
    {

        $response = $this->post('/api/auth/register', [
            'nric' => 'S1234567Z',
            'email' => 'test@test.com',
            'name' => 'testing name',
            'password' => '123456',
            'password_confirmation' => '123456',
            'telephone_number' => '123456789',
        ]);
        
        $response->assertStatus(422);

        $this->assertDatabaseMissing('users', [
            'nric' => 'S1234567Z',
        ]);
    }

    /** @test */
    public function it_cannot_register_user_account_with_mismatched_password()
    {

        $response = $this->post('/api/auth/register', [
            'nric' => 'S1234567Z',
            'email' => 'test@test.com',
            'name' => 'testing name',
            'password' => '1234567',
            'password_confirmation' => '123456',
            'telephone_number' => '12245678',
        ]);
        
        $response->assertStatus(422);

        $this->assertDatabaseMissing('users', [
            'nric' => 'S1234567Z',
        ]);
    }

    /** @test */
    public function it_cannot_register_user_account_with_password_shorter_than_6_character()
    {

        $response = $this->post('/api/auth/register', [
            'nric' => 'S1234567Z',
            'email' => 'test@test.com',
            'name' => 'testing name',
            'password' => '12345',
            'password_confirmation' => '12345',
            'telephone_number' => '12245678',
        ]);
        
        $response->assertStatus(422);

        $this->assertDatabaseMissing('users', [
            'nric' => 'S1234567Z',
        ]);
    }

    /** @test */
    public function it_cannot_register_user_account_with_password_longer_than_30_character()
    {

        $response = $this->post('/api/auth/register', [
            'nric' => 'S1234567Z',
            'email' => 'test@test.com',
            'name' => 'testing name',
            'password' => '1234567890223456789032345678901',
            'password_confirmation' => '1234567890223456789032345678901',
            'telephone_number' => '12345678',
        ]);
        
        $response->assertStatus(422);

        $this->assertDatabaseMissing('users', [
            'nric' => 'S1234567Z',
        ]);
    }

    /** @test */
    public function it_cannot_register_user_account_with_wrong_email_format()
    {

        $response = $this->post('/api/auth/register', [
            'nric' => 'S1234567Z',
            'email' => 'test@test',
            'name' => 'testing name',
            'password' => '123456',
            'password_confirmation' => '123456',
            'telephone_number' => '12245678',
        ]);
        
        $response->assertStatus(422);

        $this->assertDatabaseMissing('users', [
            'nric' => 'S1234567Z',
        ]);
    }

    /** @test */
    public function it_cannot_register_user_account_with_any_empty_fields()
    {

        $response = $this->post('/api/auth/register', [
            'nric' => '',
            'email' => 'test@test.com',
            'name' => 'testing name',
            'password' => '123456',
            'password_confirmation' => '123456',
            'telephone_number' => '12245678',
        ]);
        
        $response->assertStatus(422);

        $this->assertDatabaseMissing('users', [
            'email' => 'test@test.com',
        ]);


        $response = $this->post('/api/auth/register', [
            'nric' => 'S1234567Z',
            'email' => '',
            'name' => 'testing name',
            'password' => '123456',
            'password_confirmation' => '123456',
            'telephone_number' => '12245678',
        ]);
        
        $response->assertStatus(422);

        $this->assertDatabaseMissing('users', [
            'nric' => 'S1234567Z',
        ]);


        $response = $this->post('/api/auth/register', [
            'nric' => 'S1234567Z',
            'email' => 'test@test.com',
            'name' => '',
            'password' => '123456',
            'password_confirmation' => '123456',
            'telephone_number' => '12245678',
        ]);
        
        $response->assertStatus(422);

        $this->assertDatabaseMissing('users', [
            'nric' => 'S1234567Z',
        ]);


        $response = $this->post('/api/auth/register', [
            'nric' => 'S1234567Z',
            'email' => 'test@test.com',
            'name' => 'testing name',
            'password' => '',
            'password_confirmation' => '123456',
            'telephone_number' => '12245678',
        ]);
        
        $response->assertStatus(422);

        $this->assertDatabaseMissing('users', [
            'nric' => 'S1234567Z',
        ]);


        $response = $this->post('/api/auth/register', [
            'nric' => 'S1234567Z',
            'email' => 'test@test.com',
            'name' => 'testing name',
            'password' => '123456',
            'password_confirmation' => '',
            'telephone_number' => '12245678',
        ]);
        
        $response->assertStatus(422);

        $this->assertDatabaseMissing('users', [
            'nric' => 'S1234567Z',
        ]);


        $response = $this->post('/api/auth/register', [
            'nric' => 'S1234567Z',
            'email' => 'test@test.com',
            'name' => 'testing name',
            'password' => '123456',
            'password_confirmation' => '123456',
            'telephone_number' => '',
        ]);
        
        $response->assertStatus(422);

        $this->assertDatabaseMissing('users', [
            'nric' => 'S1234567Z',
        ]);

    }

    /** @test*/
    public function it_can_log_a_user_in()
    {
        $response = $this->post('/api/auth/register', [
            'nric' => 'S1234567Z',
            'email' => 'test@test.com',
            'name' => 'testing name',
            'password' => '123456',
            'password_confirmation' => '123456',
            'telephone_number' => '12345678',
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
    }

    /** @test*/
    public function it_cannot_log_a_wrong_user_in()
    {
        $response = $this->post('/api/auth/register', [
            'nric' => 'S1234567Z',
            'email' => 'test@test.com',
            'name' => 'testing name',
            'password' => '123456',
            'password_confirmation' => '123456',
            'telephone_number' => '12345678',
        ]);
        
        $response->assertStatus(200);

        $this->assertDatabaseHas('users', [
            'nric' => 'S1234567Z',
        ]);

        $login = $this->post('/api/auth/login',[
            'nric' => 'S1111111Z',
            'password' => '123456',
        ]);

        $login->assertStatus(401);
        $login->assertJson(['error'  => "Unauthorized"]);
    }
}
