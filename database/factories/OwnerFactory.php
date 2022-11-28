<?php

namespace Database\Factories;

use App\Models\{Company\Company, Owner\Owner, User\User};
use App\Traits\FactoryDeletedState;
use Illuminate\Database\Eloquent\Factories\Factory;

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
     * Indicate that generated owner will be generated without company.
     *
     * @var bool
     */
    private bool $withoutCompany = false;

    /**
     * Configure the model factory.
     *
     * @return $this
     */
    public function configure(): static
    {
        return $this->afterMaking(function (Owner $owner) {
            if (!$owner->user_id) {
                $user = User::factory()->create();
                $user->assignRole('owner');
                $owner->user()->associate($user);
            }

            if (!$owner->company_id and !$this->withoutCompany) {
                $company = Company::factory()->create();
                $owner->company()->associate($company);
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
        return [
            'is_prime_owner' => true,
        ];
    }

    /**
     * Indicate that the model's prime owner.
     *
     * @return Factory
     */
    public function prime(): Factory
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
     * @return Factory
     */
    public function notPrime(): Factory
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
     * @return Factory
     */
    public function withoutCompany(): Factory
    {
        $this->withoutCompany = true;

        return $this->state(function (array $attributes) {
            return [
                'company_id' => null,
            ];
        });
    }
}
