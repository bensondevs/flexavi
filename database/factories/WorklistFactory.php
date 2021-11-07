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
     * Configure the model factory.
     *
     * @return $this
     */
    public function configure()
    {
        return $this->afterMaking(function (Worklist $worklist) {
            if (! $worklist->company_id) {
                $company = Company::inRandomOrder()->first() ?:
                    Company::factory()->create();
                $worklist->company()->associate($company);
            }

            if (! $worklist->workday_id) {
                $workday = Workday::where('company_id', $worklist->company_id)
                    ->inRandomOrder()
                    ->first() ?: Workday::factory()->for($worklist->company)->create();
                $worklist->workday()->associate($workday);
            }
        });
    }

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $faker = $this->faker;

        $status = WorklistStatus::Prepared;

        return [
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
