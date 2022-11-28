<?php

namespace Database\Factories;

use App\Enums\WorkService\WorkServiceStatus;
use App\Models\{Company\Company, WorkService\WorkService};
use App\Traits\FactoryDeletedState;
use Illuminate\Database\Eloquent\Factories\Factory;

class WorkServiceFactory extends Factory
{
    use FactoryDeletedState;

    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = WorkService::class;

    /**
     * Configure the model factory.
     *
     * @return $this
     */
    public function configure()
    {
        return $this->afterMaking(function (WorkService $workService) {
            if (!$workService->company_id) {
                $company = Company::factory()->create();
                $workService->company()->associate($company);
            }
        });
    }

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition(): array
    {
        return [
            'name' => $this->faker->sentence,
            'price' => $this->faker->randomNumber(4, true),
            'description' => $this->faker->paragraph,
            'status' => WorkServiceStatus::Active,
            'tax_percentage' => $this->faker->numberBetween(5, 11),
            'unit' => 'm2',
        ];
    }

    /**
     * Indicate that the model's that active.
     *
     * @return Factory
     */
    public function active(): Factory
    {
        return $this->state(function (array $attributes) {
            return [
                'status' => WorkServiceStatus::Active,
            ];
        });
    }

    /**
     * Indicate that the model's that inactive.
     *
     * @return Factory
     */
    public function inactive(): Factory
    {
        return $this->state(function (array $attributes) {
            return [
                'status' => WorkServiceStatus::Inactive,
            ];
        });
    }
}
