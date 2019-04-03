<?php

use Faker\Generator as Faker;

$factory->define(App\Crisis::class, function (Faker $faker) {
    return [
        'user_id' =>'1', 
        'name' => $faker->name, 
        'telephone_number' => '12341234', 
        'postal_code' => '123456', 
        'date' => '2019/01/01', 
        'time' => '12:30:00', 
        'address' => $faker->address, 
        'crisis_type' => 'Fire Outbreak', 
        'status' => 'registered', 
        'description' => 'this is a short description of the crisis', 
        // 'twitter_post_id' => null, 
        'facebook_post_id' => null
    ];
});
