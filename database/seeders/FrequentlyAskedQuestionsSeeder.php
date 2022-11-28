<?php

namespace Database\Seeders;

use App\Models\FAQ\FrequentlyAskedQuestion;
use Illuminate\Database\Seeder;

class FrequentlyAskedQuestionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $faker = \Faker\Factory::create();

        $rawFaqs = [];

        for ($i = 0; $i <= 100; $i++) {
            array_push($rawFaqs, [
                'id' => generateUuid(),
                'title' => $faker->sentence,
                'content' => $faker->paragraph,
                'created_at' => carbon()->now(),
                'updated_at' => carbon()->now(),
            ]);
        }

        FrequentlyAskedQuestion::insert($rawFaqs);
    }
}
