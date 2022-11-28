<?php

namespace Database\Factories;

use App\Enums\Setting\WorkContract\WorkContractContentPositionType;
use App\Enums\Setting\WorkContract\WorkContractContentTextType;
use App\Models\WorkContract\WorkContract;
use App\Models\WorkContract\WorkContractContent;
use Illuminate\Database\Eloquent\Factories\Factory;

class WorkContractContentFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = WorkContractContent::class;

    /**
     * Configure the model factory.
     *
     * @return $this
     */
    public function configure(): static
    {
        return $this->afterMaking(function (WorkContractContent $workContractContent) {
            if (!$workContractContent->work_contract_id) {
                $workContract = WorkContract::factory()->create();
                $workContractContent->workContract()->associate($workContract);
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
            'order_index' => $this->faker->randomNumber(),
            'position_type' => WorkContractContentPositionType::getRandomValue(),
            'text_type' => WorkContractContentTextType::getRandomValue(),
            'text' => $this->faker->text(),
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }

    /**
     * Indicate that the model's that foreword.
     *
     * @return Factory
     */
    public function foreword(): Factory
    {
        return $this->state(function (array $attributes) {
            return [
                'position_type' => WorkContractContentPositionType::Foreword,
            ];
        });
    }

    /**
     * Indicate that the model's that contract.
     *
     * @return Factory
     */
    public function contract(): Factory
    {
        return $this->state(function (array $attributes) {
            return [
                'position_type' => WorkContractContentPositionType::Contract,
            ];
        });
    }
}
