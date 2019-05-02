<?php

use Illuminate\Database\Seeder;

class UserTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('users')->insert([
          'name' => 'Ismael',
          'email' => 'ismael@ileadserve.com',
          'password' => bcrypt('Lizardlips00'),
          'api_token' => Str::random(60),
          'portfolio' => 'CCL',
          'team' => 'kelsea',
          'position' => 'RM'
        ]);

        DB::table('users')->insert([
          'name' => 'Ryan',
          'email' => 'ryan@ileadserve.com',
          'password' => bcrypt('Lizardlips00'),
          'api_token' => Str::random(60),
          'portfolio' => 'CCL',
          'team' => 'kelsea',
          'position' => 'TL'
        ]);

        DB::table('users')->insert([
          'name' => 'Kelsea',
          'email' => 'kelsea@ileadserve.com',
          'password' => bcrypt('Lizardlips00'),
          'api_token' => Str::random(60),
          'portfolio' => 'CCL',
          'team' => 'kelsea',
          'position' => 'TL'
        ]);

        DB::table('users')->insert([
          'name' => 'Anne',
          'email' => 'anne@ileadserve.com',
          'password' => bcrypt('Lizardlips00'),
          'api_token' => Str::random(60),
          'portfolio' => 'PCL',
          'team' => 'jess',
          'position' => 'RM'
        ]);

        DB::table('users')->insert([
          'name' => 'Nancy',
          'email' => 'nancy@ileadserve.com',
          'password' => bcrypt('Lizardlips00'),
          'api_token' => Str::random(60),
          'portfolio' => 'PCL',
          'team' => 'jess',
          'position' => 'RM'
        ]);
    }
}
