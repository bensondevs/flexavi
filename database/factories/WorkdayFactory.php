<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Traits\FactoryDeletedState;

use App\Models\{ Company, Workday };

use App\Enums\Workday\WorkdayStatus as Status;

class WorkdayFactory extends Factory
{
    use FactoryDeletedState;

    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Workday::class;

    /**
     * Configure the model factory.
     *
     * @return $this
     */
    public function configure()
    {
        return $this->afterMaking(function (Workday $workday) {
            if (! $workday->company_id) {
                $company = Company::inRandomOrder()->first() ?:
                    Company::factory()->create();
                $workday->company()->associate($company);
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

        return [
            'date' => $faker->date(),
            'status' => rand(Status::Prepared, Status::Calculated),
        ];
    }

    /**
     * Indicate that the model's that has status of prepared.
     *
     * @return \Illuminate\Database\Eloquent\Factories\Factory
     */
    public function prepared()
    {
        return $this->state(function (array $attributes) {
            return [
                'status' => Status::Prepared,
            ];
        });
    }

    /**
     * Indicate that the model's that has status of processed.
     *
     * @return \Illuminate\Database\Eloquent\Factories\Factory
     */
    public function processed()
    {
        return $this->state(function (array $attributes) {
            return [
                'status' => Status::Processed,
            ];
        });
    }

    /**
     * Indicate that the model's that has status of calculated.
     *
     * @return \Illuminate\Database\Eloquent\Factories\Factory
     */
    public function calculated()
    {
        return $this->state(function (array $attributes) {
            return [
                'status' => Status::Calculated,
            ];
        });
    }
}