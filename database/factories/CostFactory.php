<?php

namespace Database\Factories;

use App\Models\{Company\Company, Cost\Cost};
use App\Traits\FactoryDeletedState;
use Illuminate\Database\Eloquent\Factories\Factory;

class CostFactory extends Factory
{
    use FactoryDeletedState;

    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Cost::class;

    /**
     * Configure the model factory.
     *
     * @return $this
     */
    public function configure()
    {
        return $this->afterMaking(function (Cost $cost) {
            if (!$cost->company_id) {
                $company = Company::factory()->create();
                $cost->company()->associate($company);
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
        $amount = $this->faker->randomNumber(5, true);
        return [
            'cost_name' => $this->faker->word,
            'amount' => $amount,
            'paid_amount' => $amount - $this->faker->numberBetween(0, $amount),
        ];
    }

    /**
     * Indicate that the model's already settled.
     *
     * @return Factory
     */
    public function settled()
    {
        return $this->state(function (array $attributes) {
            $amount = $this->faker->randomNumber(5, true);
            return [
                'amount' => $amount,
                'paid_amount' => $amount,
            ];
        });
    }
}
