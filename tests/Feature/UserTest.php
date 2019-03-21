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
            'password' => '123123',
        ]);

        $login->assertStatus(200);
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
            'password' => '123456',
        ]);

        $login->assertStatus(401);
        $login->assertJson(['error'  => "Unauthorized"]);
    }

    /** @test */
    public function it_cannot_register_user_account_if_unauthenticated()
    {

        $response = $this->post('/api/auth/register', [
            'name' => 'testing name',
            'nric' => 'S9876543Z',
            'email' => 'test@test.com',
            'password' => '123456',
            'password_confirmation' => '123456',
            'telephone_number' => '12245678',
            'roles' => 'CallCenterOperator'
        ]);
        
        $response->assertStatus(405);

        $this->assertDatabaseMissing('users', [
            'nric' => 'S9876543Z',
        ]);

    }
 
    /** @test */
    public function it_can_register_all_user_roles_as_accountmanager()
    {
        $user = factory(User::class)
            ->states('AccountManager')
            ->create();
            
        $login = $this->post('/api/auth/login',[
            'nric' => $user->nric,
            'password' => '123123',
        ]);

        $login->assertStatus(200);

        $roles = ['CallCenterOperator', 'AccountManager', 'CivilDefencesAdmin', 'AccountManager'];

        foreach($roles as $role){
            $nric = "S".random_int(9000000 ,9999999)."Z";

            $response = $this->post('/api/register', [
                'nric' => $nric,
                'email' => $this->faker->email(),
                'name' => $this->faker->firstName(),
                'password' => '123456',
                'password_confirmation' => '123456',
                'telephone_number' => random_int(90000000 ,99999999),
                'roles' => $role,
            ]);
            
            $response->assertStatus(200);

            $this->assertDatabasehas('users', [
                'nric' => $nric,
            ]);
        }
    }

    /** @test */
    public function it_cannot_register_all_user_roles_as_other_roles()
    {
        $roles = ['CallCenterOperator', 'CrisisManager', 'CivilDefencesAdmin'];

        foreach($roles as $role){
                
            $user = factory(User::class)
                ->states($role)
                ->create();
                
            $login = $this->post('/api/auth/login',[
                'nric' => $user->nric,
                'password' => '123123',
            ]);

            $login->assertStatus(200);

            $nric = "S".random_int(9000000 ,9999999)."Z";

            $response = $this->post('/api/register', [
                'nric' => $nric,
                'email' => $this->faker->email(),
                'name' => $this->faker->firstName(),
                'password' => '123456',
                'password_confirmation' => '123456',
                'telephone_number' => random_int(90000000 ,99999999),
                'roles' => $role,
            ]);
            
            $response->assertStatus(401);

            $this->assertDatabaseMissing('users', [
                'nric' => $nric,
            ]);
        }
    }

    /** @test */
    public function it_cannot_register_user_account_with_same_email()
    {
        $user = factory(User::class)
            ->states('AccountManager')
            ->create();
        
        $login = $this->post('/api/auth/login',[
            'nric' => $user->nric,
            'password' => '123123',
        ]);

        $login->assertStatus(200);

        $email = $this->faker->email();

        $response = $this->post('/api/register', [
            'nric' =>  "S9765432Z",
            'email' => $email,
            'name' => $this->faker->firstName(),
            'password' => '123456',
            'password_confirmation' => '123456',
            'telephone_number' => random_int(90000000 ,99999999),
            'roles' => 'AccountManager',
        ]);
        
        $response->assertStatus(200);

        $this->assertDatabasehas('users', [
            'nric' =>  "S9765432Z",
        ]);

        $response = $this->post('/api/register', [
            'nric' =>  "S9765431Z",
            'email' => $email,
            'name' => $this->faker->firstName(),
            'password' => '123456',
            'password_confirmation' => '123456',
            'telephone_number' => random_int(90000000 ,99999999),
            'roles' => 'AccountManager',
        ]);
 
        $response->assertStatus(422);
    }






    
    /** @test */
    public function it_cannot_register_user_account_with_short_nric()
    {
        $user = factory(User::class)
            ->states('AccountManager')
            ->create();
        
        $login = $this->post('/api/auth/login',[
            'nric' => $user->nric,
            'password' => '123123',
        ]);

        $login->assertStatus(200);

        $email = $this->faker->email();

        $response = $this->post('/api/register', [
            'nric' =>  "S9765432Z",
            'email' => $email,
            'name' => $this->faker->firstName(),
            'password' => '123456',
            'password_confirmation' => '123456',
            'telephone_number' => random_int(90000000 ,99999999),
            'roles' => 'AccountManager',
        ]);
        
        $response->assertStatus(200);

        $this->assertDatabasehas('users', [
            'nric' =>  "S9765432Z",
        ]);

        $response = $this->post('/api/register', [
            'nric' =>  "S9765431Z",
            'email' => $email,
            'name' => $this->faker->firstName(),
            'password' => '123456',
            'password_confirmation' => '123456',
            'telephone_number' => random_int(90000000 ,99999999),
            'roles' => 'AccountManager',
        ]);
 
        $response->assertStatus(422);
    }

    /** @test */
    public function it_cannot_register_user_account_with_long_nric()
    {

    }

    /** @test */
    public function it_cannot_register_user_account_with_short_telephone()
    {

    }

    /** @test */
    public function it_cannot_register_user_account_with_long_telephone()
    {

    }

    /** @test */
    public function it_cannot_register_user_account_with_mismatched_password()
    {

    }

    /** @test */
    public function it_cannot_register_user_account_with_password_shorter_than_6_character()
    {

    }

    /** @test */
    public function it_cannot_register_user_account_with_password_longer_than_30_character()
    {

    }
    
    /** @test */
    public function it_cannot_register_user_account_with_wrong_email_format()
    {

    }

    /** @test */
    public function it_cannot_register_user_account_with_any_empty_fields()
    {

    }

}