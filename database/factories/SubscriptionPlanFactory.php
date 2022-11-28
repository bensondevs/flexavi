<?php

namespace Database\Factories;

use App\Models\Subscription\SubscriptionPlan;
use Illuminate\Database\Eloquent\Factories\Factory;

class SubscriptionPlanFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = SubscriptionPlan::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition(): array
    {
        return [
            'name' => $this->faker->randomElement(['STARTER', 'EXCLUSIVE']),
            'description' => $this->faker->words(2),
            'base_price' => rand(100, 300)
        ];
    }


    /**
     * Indicate that the model's that trial.
     *
     * @return Factory
     */
    public function trial(): Factory
    {
        return $this->state(function (array $attributes) {
            return [
                'name' => 'TRIAL',
                'base_price' => 0,
                'is_trial' => true,
                'description' => 'Trial subscription plan'
            ];
        });
    }
}
