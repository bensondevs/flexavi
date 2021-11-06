<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Traits\FactoryDeletedState;

use App\Models\{ Company, Cost };

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
            if (! $cost->company_id) {
                $company = Company::inRandomOrder()->first() ?:
                    Company::factory()->create();
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
        $faker = $this->faker;

        $amount = $faker->randomNumber(4, false);
        $paidAmount = $amount - rand(0, $amount);

        return [
            'cost_name' => $faker->word(),
            'amount' => $amount,
            'paid_amount' => $paidAmount,
        ];
    }

    /**
     * Indicate that the model's already settled.
     *
     * @return \Illuminate\Database\Eloquent\Factories\Factory
     */
    public function settled()
    {
        return $this->state(function (array $attributes) {
            $amount = $this->faker->randomNumber(4, false);
            return [
                'amount' => $amount,
                'paid_amount' => $amount,
            ];
        });
    }
}
