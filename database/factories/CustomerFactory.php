<?php

namespace Database\Factories;

use App\Enums\Customer\{CustomerAcquisition, CustomerSalutation};
use App\Models\{Company\Company, Customer\Customer};
use App\Traits\FactoryDeletedState;
use Illuminate\Database\Eloquent\Factories\Factory;

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
    public function configure(): static
    {
        return $this->afterMaking(function (Customer $customer) {
            if (!$customer->company_id) {
                $company = Company::factory()->create();
                $customer->company_id = $company->id;
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
            'unique_key' => randomToken(),
            'salutation' => $this->faker->randomElement([
                CustomerSalutation::Mr,
                CustomerSalutation::Mrs,
            ]),
            'fullname' => $this->faker->unique()->name,
            'email' => $this->faker->unique()->safeEmail,
            'phone' => $this->faker->unique()->phoneNumber,
            'second_phone' => $this->faker->unique()->phoneNumber,
            'acquired_through' => $this->faker->randomElement([
                CustomerAcquisition::Website,
                CustomerAcquisition::Call,
                CustomerAcquisition::Company,
            ]),
        ];
    }
}
