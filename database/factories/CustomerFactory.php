<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Traits\FactoryDeletedState;

use App\Models\{ Customer, Company };

class CustomerFactory extends Factory
{
    use FactoryDeletedState;

    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Customer::class;

    /**
     * Configure the model factory.
     *
     * @return $this
     */
    public function configure()
    {
        return $this->afterMaking(function (Customer $customer) {
            if (! $customer->company_id) {
                $company = Company::inRandomOrder()->first();
                $customer->company()->associate($company);
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

        return [
            'fullname' => $faker->name(),
            'email' => $faker->safeEmail(),
            'phone' => $faker->phoneNumber(),
            'unique_key' => random_string(5),
        ];
    }
}