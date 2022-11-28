<?php

namespace Database\Factories;

use App\Models\WorkContract\WorkContract;
use App\Models\WorkContract\WorkContractService;
use App\Models\WorkService\WorkService;
use Illuminate\Database\Eloquent\Factories\Factory;

class WorkContractServiceFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = WorkContractService::class;

    /**
     * Configure the model factory.
     *
     * @return $this
     */
    public function configure(): static
    {
        return $this->afterMaking(function (WorkContractService $workContractService) {
            if (!$workContractService->work_contract_id) {
                $workContract = WorkContract::factory()->create();
                $workContractService->workContract()->associate($workContract);
            }
            if (!$workContractService->work_service_id) {
                $workService = WorkService::factory()->create();
                $workContractService->workService()->associate($workService);
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
            'amount' => rand(1, 5),
            'unit_price' => rand(100, 1000),
            'total' => rand(100, 1000),
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }
}
