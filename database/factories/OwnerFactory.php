<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Traits\FactoryDeletedState;

use App\Models\{ Owner, User, Company, Address };

class OwnerFactory extends Factory
{
    use FactoryDeletedState;

    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Owner::class;

    /**
     * Configure the model factory.
     *
     * @return $this
     */
    public function configure()
    {
        return $this->afterMaking(function (Owner $owner) {
            if (! $owner->user_id) {
                $user = User::factory()->create();
                $user->assignRole('owner');
                $owner->user()->associate($user);
            }

            if (! $owner->company_id) {
                $company = Company::inRandomOrder()->first() ?:
                    Company::factory()->create();
                $owner->company()->associate($company);
            }
        })->afterCreating(function (Owner $owner) {
            if (! $owner->user_id) {
                $user = User::factory()->create();
                $owner->user()->associate($user);
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
            'is_prime_owner' => true,
            'bank_name' => $faker->word(),
            'bic_code' => $faker->randomNumber(3, true),
            'bank_account' => $faker->randomNumber(5, true),
            'bank_holder_name' => $faker->name(),
        ];
    }

    /**
     * Indicate that the model's prime owner.
     *
     * @return \Illuminate\Database\Eloquent\Factories\Factory
     */
    public function prime()
    {
        return $this->state(function (array $attributes) {
            return [
                'is_prime_owner' => true,
            ];
        });
    }

    /**
     * Indicate that the model's not prime owner.
     *
     * @return \Illuminate\Database\Eloquent\Factories\Factory
     */
    public function notPrime()
    {
        return $this->state(function (array $attributes) {
            return [
                'is_prime_owner' => false,
            ];
        });
    }

    /**
     * Indicate that the model's has no Company.
     *
     * @return \Illuminate\Database\Eloquent\Factories\Factory
     */
    public function withoutCompany()
    {
        return $this->state(function (array $attributes) {
            return [
                'company_id' => null,
            ];
        });
    }
}
