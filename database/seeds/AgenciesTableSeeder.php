<?php
/* Author: Li JinQuan */

use Illuminate\Database\Seeder;

class AgenciesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('agencies')->insert([
            'agency' => 'Singapore Civil Defence Force',
            'assistance' => 'Emergency Ambulance',
            'hotline' => '96325376',
        ]);

        DB::table('agencies')->insert([
            'agency' => 'Singapore Civil Defence Force',
            'assistance' => 'Rescue and Evacuation',
            'hotline' => '96325376',
        ]);

        DB::table('agencies')->insert([
            'agency' => 'Singapore Civil Defence Force',
            'assistance' => 'Fire-Fighting',
            'hotline' => '96325376',
        ]);

        DB::table('agencies')->insert([
            'agency' => 'Singapore Power',
            'assistance' => 'Gas Leak Control',
            'hotline' => '96325376',
        ]);
    }
}
