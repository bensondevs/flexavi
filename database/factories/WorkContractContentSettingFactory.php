<?php

namespace Database\Factories;

use App\Enums\Setting\WorkContract\WorkContractContentPositionType;
use App\Enums\Setting\WorkContract\WorkContractContentTextType;
use App\Models\Setting\WorkContractContentSetting;
use App\Traits\FactoryDeletedState;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\WorkContractContentSetting>
 */
class WorkContractContentSettingFactory extends Factory
{
    use FactoryDeletedState;

    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = WorkContractContentSetting::class;

    /**
     * Configure the model factory.
     *
     * @return $this
     */
    public function configure(): static
    {
        return $this->afterCreating(function (WorkContractContentSetting $workContractSetting) {
            return $this;
        });
    }

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'order_index' => rand(1, 2),
            'position_type' => WorkContractContentPositionType::getRandomValue(),
            'text_type' => WorkContractContentTextType::getRandomValue(),
            'text' => $this->faker->text,
        ];
    }
}
