<?php

use Illuminate\Database\Seeder;

class CannedMessagesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('canned_messages')->insert([
          'created_by' => 1,
          'updated_by' => 1,
          'message' => 'Can I get your help, please?',
          'possible_responses' => '["Yes", "No","In a moment"]',
        ]);

        DB::table('canned_messages')->insert([
          'created_by' => 1,
          'updated_by' => 1,
          'message' => 'Are you available?',
          'possible_responses' => '["Yes", "No","In a moment"]',
        ]);

        DB::table('canned_messages')->insert([
          'created_by' => 1,
          'updated_by' => 1,
          'message' => 'I would love to say something to you real quick!',
          'possible_responses' => '["Ok","I\'ll be right there","I\'ll can\'t leave my seat"]',
        ]);

        DB::table('canned_messages')->insert([
          'created_by' => 1,
          'updated_by' => 1,
          'message' => 'Can we go chat? I need some validation!',
          'possible_responses' => '["Yes", "No", "After this call"]',
        ]);

        DB::table('canned_messages')->insert([
          'created_by' => 1,
          'updated_by' => 1,
          'message' => 'Will you come here, please?',
          'possible_responses' => '["Yes", "No", "I can\'t leave my seat", "In a moment"]',
        ]);
    }
}
