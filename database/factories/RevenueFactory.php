<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Traits\FactoryDeletedState;

use App\Models\{ Company, Revenue, Revenueable };

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
            if (! $revenue->company_id) {
                $company = Company::inRandomOrder()->first();
                $revenue->company()->associate($company);
            }
        })->afterCreating(function (Revenue $revenue) {
            if (! $revenue->revenueables()->exists()) {
                $company = $revenue->company;
                $revenueables = Revenueable::factory()
                    ->create([
                        'revenueable_id' => $revenue->id, 
                        'revenueable_type' => Revenue::class
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
        $faker = $this->faker;

        $amount = rand(100, 1000);
        return [
            'revenue_name' => $faker->word(),
            'amount' => $amount,
            'paid_amount' => rand(0, $amount),
        ];
    }
}
