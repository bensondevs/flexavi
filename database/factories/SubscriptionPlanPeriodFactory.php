<?php

namespace Database\Factories;

use App\Enums\SubscriptionPlanPeriod\DurationType;
use App\Models\Subscription\SubscriptionPlan;
use App\Models\Subscription\SubscriptionPlanPeriod;
use Illuminate\Database\Eloquent\Factories\Factory;

class SubscriptionPlanPeriodFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = SubscriptionPlanPeriod::class;

    /**
     * Configure the model factory.
     *
     * @return $this
     */
    public function configure(): static
    {
        return $this->afterMaking(function (SubscriptionPlanPeriod $subscriptionPlanPeriod) {
            if (!$subscriptionPlanPeriod->subscription_plan_id) {
                $plan = SubscriptionPlan::factory()->create();
                $subscriptionPlanPeriod->subscriptionPlan()->associate($plan);
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
        $durationType = DurationType::getRandomValue();
        return [
            'name' => $this->faker->word,
            'description' => $this->faker->sentence(),
            'price' => rand(100, 200),
            'total' => rand(100, 200),
            'duration_type' => $durationType,
            'duration' => 1,
            'days_duration' => config('subscription.days_duration.' . DurationType::getDescription($durationType)),
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
                'is_trial' => true,
                'price' => 0,
                'duration_type' => DurationType::Weekly,
                'duration' => 1,
                'days_duration' => 7,
            ];
        });
    }

}
