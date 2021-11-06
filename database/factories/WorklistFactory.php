<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Traits\FactoryDeletedState;

use App\Models\{ Workday, Worklist, Company };

use App\Enums\Worklist\WorklistStatus;

class WorklistFactory extends Factory
{
    use FactoryDeletedState;

    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Worklist::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $faker = $this->faker;

        if (! $company = Company::inRandomOrder()->first()) {
            $company = Company::factory()->create();
        }

        if (! $workday = $company->workdays()->inRandomOrder()->first()) {
            $workday = Workday::factory()->create(['company_id' => $company->id]);
        }

        $status = WorklistStatus::Prepared;

        return [
            'company_id' => $company->id,
            'workday_id' => $workday->id,
            'start' => $faker->datetime(),
            'end' => $faker->datetime(),

            'status' => $status,

            'worklist_name' => $faker->word(),
        ];
    }

    /**
     * Indicate that the model's has status of Prepared.
     *
     * @return \Illuminate\Database\Eloquent\Factories\Factory
     */
    public function prepared()
    {
        return $this->state(function (array $attributes) {
            return [
                'status' => WorklistStatus::Prepared,
            ];
        });
    }

    /**
     * Indicate that the model's has status of Processed.
     *
     * @return \Illuminate\Database\Eloquent\Factories\Factory
     */
    public function processed()
    {
        return $this->state(function (array $attributes) {
            return [
                'status' => WorklistStatus::Processed,
                'processed_at' => now(),
            ];
        });
    }

    /**
     * Indicate that the model's has status of Calculated.
     *
     * @return \Illuminate\Database\Eloquent\Factories\Factory
     */
    public function calculated()
    {
        return $this->state(function (array $attributes) {
            return [
                'status' => WorklistStatus::Calculated,
                'processed_at' => now(),
                'calculated_at' => now(),
            ];
        });
    }
}
