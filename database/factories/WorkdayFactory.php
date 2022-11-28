<?php

namespace Database\Factories;

use App\Enums\Workday\WorkdayStatus as Status;
use App\Models\{Company\Company, Workday\Workday};
use App\Traits\FactoryDeletedState;
use Illuminate\Database\Eloquent\Factories\Factory;

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
            if (!$workday->company_id) {
                $company = Company::factory()->create();
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
        return [
            'date' => $this->faker->date,
            'status' => $this->faker->randomElement([
                Status::Prepared,
                Status::Calculated,
            ]),
        ];
    }

    /**
     * Indicate that the model's that has status of prepared.
     *
     * @return Factory
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
     * @return Factory
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
     * @return Factory
     */
    public function calculated()
    {
        return $this->state(function (array $attributes) {
            return [
                'status' => Status::Calculated,
            ];
        });
    }

    /**
     * Indicate that the model's that has status of calculated.
     *
     * @return Factory
     */
    public function date($date)
    {
        return $this->state(function (array $attributes) use ($date) {
            return [
                'date' => $date,
            ];
        });
    }
}
