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
        ]);

        DB::table('users')->insert([
          'name' => 'Ryan',
          'email' => 'ryan@ileadserve.com',
          'password' => bcrypt('Lizardlips00'),
        ]);
    }
}
