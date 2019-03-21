<?php

use Faker\Generator as Faker;

/*
|--------------------------------------------------------------------------
| Model Factories
|--------------------------------------------------------------------------
|
| This directory should contain each of the model factory definitions for
| your application. Factories provide a convenient way to generate new
| model instances for testing / seeding your application's database.
|
*/

$factory->define(App\User::class, function (Faker $faker) {
    return [
        'name' => $faker->name,
        'email' => $faker->unique()->safeEmail,
        'password' => bcrypt('123123'), // secret
        'telephone_number' => '12341234', 
        'remember_token' => str_random(10),
    ];
});

$factory->state(\App\User::class, 'CallCenterOperator', function ($faker) {
    return [
        'roles' => 'CallCenterOperator',
        'nric' => 'S1234566Z',         
    ];
});

$factory->state(\App\User::class, 'CrisisManager', function ($faker) {
    return [
        'roles' => 'CrisisManager',
        'nric' => 'S1234567Z', 
    ];
});

$factory->state(\App\User::class, 'CivilDefencesAdmin', function ($faker) {
    return [
        'roles' => 'CivilDefencesAdmin',
        'nric' => 'S1234568Z', 
    ];
});

$factory->state(\App\User::class, 'AccountManager', function ($faker) {
    return [
        'roles' => 'AccountManager',
        'nric' => 'S1234569Z', 
    ];
});
