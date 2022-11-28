<?php

namespace Database\Factories;

use App\Models\{Company\Company, Revenue\Revenue, Revenue\Revenueable};
use App\Traits\FactoryDeletedState;
use Illuminate\Database\Eloquent\Factories\Factory;

class RevenueFactory extends Factory
{
    use FactoryDeletedState;

    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Revenue::class;

    /**
     * Configure the model factory.
     *
     * @return $this
     */
    public function configure()
    {
        return $this->afterMaking(function (Revenue $revenue) {
            if (!$revenue->company_id) {
                $company = Company::factory()->create();
                $revenue->company()->associate($company);
            }
        })->afterCreating(function (Revenue $revenue) {
            if (!$revenue->revenueables()->exists()) {
                Revenueable::factory()->create([
                    'revenueable_id' => $revenue->id,
                    'revenueable_type' => Revenue::class,
                ]);
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
        $amount = $this->faker->numberBetween(100, 1000);
        return [
            'revenue_name' => $this->faker->word,
            'amount' => $amount,
            'paid_amount' => $this->faker->numberBetween(0, $amount),
        ];
    }
}
