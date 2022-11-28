<?php

namespace Database\Factories;

use App\Models\Setting\CustomerSetting;
use App\Traits\FactoryDeletedState;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\CustomerSetting>
 */
class CustomerSettingFactory extends Factory
{
    use FactoryDeletedState;

    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = CustomerSetting::class;

    /**
     * Configure the model factory.
     *
     * @return $this
     */
    public function configure(): static
    {
        return $this->afterCreating(function (CustomerSetting $customerSetting) {
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
            'pagination' => $this->faker->randomElement([10,20,50,100]),
        ];
    }
}
