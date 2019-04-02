<?php

use Illuminate\Support\Str;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('users')->insert([
            'name' => 'JinQuan',
            'nric' => 'S1234566Z',
            'email' => Str::random(10).'@gmail.com',
            'password' => bcrypt('12345!qW'),
            'telephone_number' => random_int(90000000 ,99999999),
            'roles' => 'CallCenterOperator'
        ]);

        DB::table('users')->insert([
            'name' => 'JinQuan',
            'nric' => 'S1234567Z',
            'email' => Str::random(10).'@gmail.com',
            'password' => bcrypt('12345!qW'),
            'telephone_number' => random_int(90000000 ,99999999),
            'roles' => 'CrisisManager'
        ]);

        DB::table('users')->insert([
            'name' => 'JinQuan',
            'nric' => 'S1234569Z',
            'email' => Str::random(10).'@gmail.com',
            'password' => bcrypt('12345!qW'),
            'telephone_number' => random_int(90000000 ,99999999),
            'roles' => 'AccountManager'
        ]);
    }
}