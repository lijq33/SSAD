<?php

namespace Tests\Feature;

use Tests\TestCase;
USE Illuminate\Foundation\Testing\DatabaseMigrations;
use App\User;

use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class UserTest extends TestCase
{
    use DatabaseMigrations, WithFaker;

    public $password = '12345!qW';
    public $telephone_number = '12345678';
    public $nric = 'S9512123Z';
   
    public function loginWithAccountManager()
    {
        $user = factory(User::class)
        ->states('AccountManager')
        ->create();

        $this->post('/api/auth/login',[
            'nric' => $user->nric,
            'password' => $this->password,
        ]);
    }


    /** @test*/
    public function it_can_log_a_user_in()
    {
        $user = factory(User::class)
            ->states('CrisisManager')
            ->create();

        $this->assertDatabaseHas('users', [
            'nric' => $user->nric,
        ]);

        $login = $this->post('/api/auth/login',[
            'nric' => $user->nric,
            'password' => $this->password,
        ]);

        $login->assertStatus(200);
    }

    /** @test*/
    public function it_can_log_a_user_out()
    {
        $this->loginWithAccountManager();

        $response = $this->post('/api/auth/logout');

        $response->assertStatus(200);
        $response->assertJson(['message' => 'Successfully logged out']);
    }

        
    /** @test*/
    public function it_cannot_log_a_user_in_with_incorrect_credentials()
    {
        $user = factory(User::class)
            ->states('CrisisManager')
            ->create();

        $this->assertDatabaseHas('users', [
            'nric' => $user->nric,
        ]);

        $login = $this->post('/api/auth/login',[
            'nric' => 'S1111111Z',
            'password' => $this->password,
        ]);

        $login->assertStatus(401);
        $login->assertJson(['error'  => "Unauthorized"]);
    }

    /** @test */
    public function it_cannot_register_user_account_if_unauthenticated()
    {

        $response = $this->post('/api/register', [
            'name' => 'testing name',
            'nric' => $this->nric,
            'email' => $this->faker->email(),
            'password' => $this->password,
            'password_confirmation' => $this->password,
            'telephone_number' => $this->telephone_number,
            'roles' => 'CallCenterOperator'
        ]);
        
        $response->assertStatus(401);

        $this->assertDatabaseMissing('users', [
            'nric' => $this->nric,
        ]);

    }
 
    /** @test */
    public function it_can_register_all_user_roles_as_accountmanager()
    {
       $this->loginWithAccountManager();

        $roles = ['CallCenterOperator', 'AccountManager', 'CivilDefencesAdmin', 'AccountManager'];

        foreach($roles as $role){
            $nric = "S".random_int(9000000 ,9999999)."Z";

            $response = $this->post('/api/register', [
                'nric' => $nric,
                'email' => $this->faker->email(),
                'name' => $this->faker->firstName(),
                'password' => $this->password,
                'password_confirmation' => $this->password,
                'telephone_number' => $this->telephone_number,
                'roles' => $role,
            ]);
            
            $response->assertStatus(200);

            $this->assertDatabasehas('users', [
                'nric' => $nric,
            ]);
        }
    }

    /** @test */
    public function it_cannot_register_user_if_roles_is_not_AccountManager()
    {
        $roles = ['CallCenterOperator', 'CrisisManager', 'CivilDefencesAdmin'];

        foreach($roles as $role){
                
            $user = factory(User::class)
                ->states($role)
                ->create();
                
            $login = $this->post('/api/auth/login',[
                'nric' => $user->nric,            
                'password' => $this->password,
            ]);

            $login->assertStatus(200);

            $response = $this->post('/api/register', [
                'nric' => $this->nric,
                'email' => $this->faker->email(),
                'name' => $this->faker->firstName(),
                'password' => $this->password,
                'password_confirmation' => $this->password,
                'telephone_number' => $this->telephone_number,
                'roles' => $role,
            ]);
            
            $this->assertDatabaseMissing('users', [
                'nric' => $this->nric,
            ]);


        }
    }

    /** @test */
    public function it_cannot_register_user_account_with_same_email()
    {
        
        $this->loginWithAccountManager();

        $email = $this->faker->email();

        $response = $this->post('/api/register', [
            'nric' =>  $this->nric,
            'email' => $email,
            'name' => $this->faker->firstName(),
            'password' => $this->password,
            'password_confirmation' => $this->password,
            'telephone_number' => $this->telephone_number,
            'roles' => 'AccountManager',
        ]);
        
        $response->assertStatus(200);

        $this->assertDatabasehas('users', [
            'nric' =>  $this->nric,
        ]);

        $response = $this->post('/api/register', [
            'nric' =>  "S9765431Z",
            'email' => $email,
            'name' => $this->faker->firstName(),
            'password' => $this->password,
            'password_confirmation' => $this->password,
            'telephone_number' => $this->telephone_number,
            'roles' => 'AccountManager',
        ]);
 
        $response->assertStatus(422);
    }

    /** @test */
    public function it_cannot_register_user_account_with_incorrect_nric_format()
    {
        
        $this->loginWithAccountManager();

        //Short NRIC
        $response = $this->post('/api/register', [
            'nric' =>  "S976543Z",
            'email' => $this->faker->email(),
            'name' => $this->faker->firstName(),
            'password' => $this->password,
            'password_confirmation' => $this->password,
            'telephone_number' => $this->telephone_number,
            'roles' => 'AccountManager',
        ]);
        
        $response->assertStatus(422);

        $this->assertDatabaseMissing('users', [
            'nric' =>  "S976543Z",
        ]);

        //Long NRIC
        $response = $this->post('/api/register', [
            'nric' =>  "S97654322Z",
            'email' => $this->faker->email(),
            'name' => $this->faker->firstName(),
            'password' => $this->password,
            'password_confirmation' => $this->password,
            'telephone_number' => $this->telephone_number,
            'roles' => 'AccountManager',
        ]);
        
        $response->assertStatus(422);

        $this->assertDatabaseMissing('users', [
            'nric' =>  "S97654322Z",
        ]);
        
        //Empty NRIC
        $response = $this->post('/api/register', [
            'nric' =>  "",
            'email' => $this->faker->email(),
            'name' => $this->faker->firstName(),
            'password' => $this->password,
            'password_confirmation' => $this->password,
            'telephone_number' => $this->telephone_number,
            'roles' => 'AccountManager',
        ]);
        
        $response->assertStatus(422);

        $this->assertDatabaseMissing('users', [
            'email' =>  $this->faker->email(),
        ]);
    }


    /** @test */
    public function it_cannot_register_user_account_with_incorrect_telephone_format()
    {
       
        $this->loginWithAccountManager();

        //Short telephone
        $response = $this->post('/api/register', [
            'nric' =>  $this->nric,
            'email' => $this->faker->email(),
            'name' => $this->faker->firstName(),
            'password' => $this->password,
            'password_confirmation' => $this->password,
            'telephone_number' => random_int(9000000 ,9999999),
            'roles' => 'AccountManager',
        ]);
        
        $response->assertStatus(422);

        $this->assertDatabaseMissing('users', [
            'nric' =>  $this->nric,
        ]);

        //Long telephone
        $response = $this->post('/api/register', [
            'nric' =>  $this->nric,
            'email' => $this->faker->email(),
            'name' => $this->faker->firstName(),
            'password' => $this->password,
            'password_confirmation' => $this->password,
            'telephone_number' => random_int(900000000 ,999999999),
            'roles' => 'AccountManager',
        ]);
        
        $response->assertStatus(422);

        $this->assertDatabaseMissing('users', [
            'nric' =>  $this->nric,
        ]);
        
        //Alphable in telephone
        $response = $this->post('/api/register', [
            'nric' =>  $this->nric,
            'email' => $this->faker->email(),
            'name' => $this->faker->firstName(),
            'password' => $this->password,
            'password_confirmation' => $this->password,
            'telephone_number' => 'a'.random_int(9000000 ,9999999),
            'roles' => 'AccountManager',
        ]);
        
        $response->assertStatus(422);

        $this->assertDatabaseMissing('users', [
            'nric' => $this->nric,
        ]);
    }

    /** @test */
    public function it_cannot_register_user_account_with_mismatched_password()
    {
        
        $this->loginWithAccountManager();

        $response = $this->post('/api/register', [
            'nric' =>  $this->nric,
            'email' => $this->faker->email(),
            'name' => $this->faker->firstName(),
            'password' => $this->password,
            'password_confirmation' => '123455',
            'telephone_number' => $this->telephone_number,
            'roles' => 'AccountManager',
        ]);
        
        $response->assertStatus(422);

        $this->assertDatabaseMissing('users', [
            'nric' =>  $this->nric,
        ]);
    }

    /** @test */
    public function it_cannot_register_user_account_with_weak_password()
    {
        
        $this->loginWithAccountManager();

        //short password
        $response = $this->post('/api/register', [
            'nric' =>  $this->nric,
            'email' => $this->faker->email(),
            'name' => $this->faker->firstName(),
            'password' => '123456',
            'password_confirmation' => '1234567',
            'telephone_number' => $this->telephone_number,
            'roles' => 'AccountManager',
        ]);
        
        $response->assertStatus(422);

        $this->assertDatabaseMissing('users', [
            'nric' =>  $this->nric,
        ]);

        
        //short password
        $response = $this->post('/api/register', [
            'nric' =>  $this->nric,
            'email' => $this->faker->email(),
            'name' => $this->faker->firstName(),
            'password' => '123456',
            'password_confirmation' => '1234567',
            'telephone_number' => $this->telephone_number,
            'roles' => 'AccountManager',
        ]);
        
        $response->assertStatus(422);

        $this->assertDatabaseMissing('users', [
            'nric' =>  $this->nric,
        ]);
    }

    /** @test */
    public function it_cannot_register_user_account_with_password_longer_than_30_character()
    {
        $this->loginWithAccountManager();

        $response = $this->post('/api/register', [
            'nric' =>  $this->nric,
            'email' => $this->faker->email(),
            'name' => $this->faker->firstName(),
            'password' => '1234567890123456789012345678901',
            'password_confirmation' => '1234567890123456789012345678901',
            'telephone_number' => $this->telephone_number,
            'roles' => 'AccountManager',
        ]);
        
        $response->assertStatus(422);

        $this->assertDatabaseMissing('users', [
            'nric' =>  $this->nric,
        ]);
    }
    
    /** @test */
    public function it_cannot_register_user_account_with_wrong_email_format()
    {
        $this->loginWithAccountManager();

        $response = $this->post('/api/register', [
            'nric' =>  $this->nric,
            'email' => "test.com",
            'name' => $this->faker->firstName(),
            'password' => $this->password,
            'password_confirmation' => $this->password,
            'telephone_number' => $this->telephone_number,
            'roles' => 'AccountManager',
        ]);
        
        $response->assertStatus(422);

        $this->assertDatabaseMissing('users', [
            'nric' =>  $this->nric,
        ]);
    }

    /** @test */
    public function it_cannot_register_user_account_with_any_empty_fields()
    {
        $this->loginWithAccountManager();

        //missing nric
        $response = $this->post('/api/register', [
            'nric' =>  '',
            'email' => "noNric@email.com",
            'name' => $this->faker->firstName(),
            'password' => $this->password,
            'password_confirmation' => $this->password,
            'telephone_number' => $this->telephone_number,
            'roles' => 'AccountManager',
        ]);
        
        $response->assertStatus(422);

        $this->assertDatabaseMissing('users', [
            'email' => "noNric@email.com",
        ]);
        
        //missing email
        $response = $this->post('/api/register', [
            'nric' =>  $this->nric,
            'email' => '',
            'name' => $this->faker->firstName(),
            'password' => $this->password,
            'password_confirmation' => $this->password,
            'telephone_number' => $this->telephone_number,
            'roles' => 'AccountManager',
        ]);
        
        $response->assertStatus(422);

        $this->assertDatabaseMissing('users', [
            'nric' =>  $this->nric,
        ]);
        
        //missing name
        $response = $this->post('/api/register', [
            'nric' =>  $this->nric,
            'email' => $this->faker->email(),
            'name' => '',
            'password' => $this->password,
            'password_confirmation' => $this->password,
            'telephone_number' => $this->telephone_number,
            'roles' => 'AccountManager',
        ]);
        
        $response->assertStatus(422);

        $this->assertDatabaseMissing('users', [
            'nric' =>  $this->nric,
        ]);
        
        //missing password
        $response = $this->post('/api/register', [
            'nric' =>  $this->nric,
            'email' => $this->faker->email(),
            'name' => $this->faker->firstName(),
            'password' => '',
            'password_confirmation' => $this->password,
            'telephone_number' => $this->telephone_number,
            'roles' => 'AccountManager',
        ]);
        
        $response->assertStatus(422);

        $this->assertDatabaseMissing('users', [
            'nric' =>  $this->nric,
        ]);

        //missing password confirmation
        $response = $this->post('/api/register', [
            'nric' =>  $this->nric,
            'email' => $this->faker->email(),
            'name' => $this->faker->firstName(),
            'password' => $this->password,
            'password_confirmation' => '',
            'telephone_number' => $this->telephone_number,
            'roles' => 'AccountManager',
        ]);
        
        $response->assertStatus(422);

        $this->assertDatabaseMissing('users', [
            'nric' =>  $this->nric,
        ]);

        //missing telephone
        $response = $this->post('/api/register', [
            'nric' =>  $this->nric,
            'email' => $this->faker->email(),
            'name' => $this->faker->firstName(),
            'password' => $this->password,
            'password_confirmation' => $this->password,
            'telephone_number' => '',
            'roles' => 'AccountManager',
        ]);

        $response->assertStatus(422);

        $this->assertDatabaseMissing('users', [
            'nric' =>  $this->nric,
        ]);

        //missing role
        $response = $this->post('/api/register', [
            'nric' =>  $this->nric,
            'email' => $this->faker->email(),
            'name' => $this->faker->firstName(),
            'password' => $this->password,
            'password_confirmation' => $this->password,
            'telephone_number' => $this->telephone_number,
            'roles' => '',
        ]);

        $response->assertStatus(422);

        $this->assertDatabaseMissing('users', [
            'nric' =>  $this->nric,
        ]);
    }

}