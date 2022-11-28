<?php

namespace Database\Factories;

use App\Enums\Worklist\WorklistSortingRouteStatus;
use App\Enums\Worklist\WorklistStatus;
use App\Models\{Company\Company, Workday\Workday, Worklist\Worklist};
use App\Traits\FactoryDeletedState;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\Factory;

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
            if (!$worklist->company_id) {
                $company = Company::factory()->create();
                $worklist->company()->associate($company);
            }
            if (!$worklist->workday_id) {
                $workday = Workday::factory()
                    ->for($worklist->company)
                    ->create();
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
        return [
            'start' => $this->faker->datetime,
            'end' => $this->faker->datetime,
            'status' => WorklistStatus::Prepared,
            'worklist_name' => $this->faker->word,
        ];
    }

    /**
     * Indicate that the model's has status of Prepared.
     *
     * @return Factory
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
     * @return Factory
     */
    public function processed()
    {
        return $this->state(function (array $attributes) {
            return [
                'status' => WorklistStatus::Processed,
                'processed_at' => Carbon::now(),
            ];
        });
    }

    /**
     * Indicate that the model's has status of Calculated.
     *
     * @return Factory
     */
    public function calculated()
    {
        return $this->state(function (array $attributes) {
            return [
                'status' => WorklistStatus::Calculated,
                'processed_at' => Carbon::now(),
                'calculated_at' => Carbon::now(),
            ];
        });
    }


    /**
     * Indicate that the model's has status of sorting route status is active.
     *
     * @return Factory
     */
    public function sortingRouteActive()
    {
        return $this->state(function (array $attributes) {
            return [
                'always_sorting_route_status' => WorklistSortingRouteStatus::Active,
                'sorting_route_status' => WorklistSortingRouteStatus::Active,
            ];
        });
    }

    /**
     * Indicate that the model's has status of sorting route status is inactive.
     *
     * @return Factory
     */
    public function sortingRouteInactive()
    {
        return $this->state(function (array $attributes) {
            return [
                'always_sorting_route_status' => WorklistSortingRouteStatus::Inactive,
                'sorting_route_status' => WorklistSortingRouteStatus::Inactive,
            ];
        });
    }
}
