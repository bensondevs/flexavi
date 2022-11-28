<?php

namespace Database\Factories;

use App\Enums\Setting\WorkContract\WorkContractSignatureType;
use App\Models\WorkContract\WorkContract;
use App\Models\WorkContract\WorkContractSignature;
use Illuminate\Database\Eloquent\Factories\Factory;

class WorkContractSignatureFactory extends Factory
{
    protected $model = WorkContractSignature::class;

    /**
     * Configure the model factory.
     *
     * @return $this
     */
    public function configure(): static
    {
        return $this->afterMaking(function (WorkContractSignature $workContractSignature) {
            if (!$workContractSignature->work_contract_id) {
                $workContract = WorkContract::factory()->create();
                $workContractSignature->workContract()->associate($workContract);
            }
        });
    }

    public function definition(): array
    {
        return [
            'name' => $this->faker->word(),
            'type' => WorkContractSignatureType::getRandomValue(),
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }
}
