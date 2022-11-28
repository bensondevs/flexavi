<?php

namespace Database\Seeders;

use App\Models\Company\Company;
use App\Models\HelpDesk\HelpDesk;
use Faker\Generator as Faker;
use Illuminate\Database\Seeder;

class HelpDesksSeeder extends Seeder
{
    /**
     * The current Faker instance.
     *
     * @var Faker
     */
    private Faker $faker;

    /**
     * the class constructor
     *
     */
    public function __construct()
    {
        $this->faker = \Faker\Factory::create();
    }

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run(): void
    {
        $companies = Company::all();

        $rawHelpDesks = [];
        foreach ($companies as $company) {
            for ($i = 0; $i < rand(5, 10); $i++) {
                $rawHelpDesks[] = [
                    'id' => generateUuid(),
                    'company_id' => $company->id,
                    'user_id' => null,
                    'title' => $this->faker->title,
                    'content' => $this->faker->text,

                    'created_at' => now()->copy()->subDays(rand(1, 10)),
                    'updated_at' => now()->copy()->subDays(rand(1, 10)),
                ];
            }
        }


        foreach (array_chunk($rawHelpDesks, 50) as $chunk) {
            HelpDesk::insert($chunk);
        }
    }
}
