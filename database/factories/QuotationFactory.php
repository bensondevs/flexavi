<?php

namespace Database\Factories;

use App\Enums\Quotation\{QuotationStatus as Status};
use App\Models\{Company\Company, Customer\Customer, Quotation\Quotation};
use App\Traits\FactoryDeletedState;
use Illuminate\Database\Eloquent\Factories\Factory;

class QuotationFactory extends Factory
{
    use FactoryDeletedState;

    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Quotation::class;

    /**
     * Configure the model factory.
     *
     * @return $this
     */
    public function configure(): static
    {
        return $this->afterMaking(function (Quotation $quotation) {
            if (!$quotation->company_id) {
                $company = Company::factory()->create();
                $quotation->company()->associate($company);
            }
            if (!$quotation->customer_id) {
                $customer = Customer::factory()->for($quotation->company)->create();
                $quotation->customer()->associate($customer);
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
            'id' => generateUuid(),
            'date' => $this->faker->date,
            'expiry_date' => $this->faker->date,
            'number' => $this->faker->randomNumber(5, true),
            'customer_address' => $this->faker->address,
            'discount_amount' => $this->faker->randomNumber(3, true),
            'amount' => $this->faker->randomNumber(4, true),
            'taxes' => json_encode([
                [
                    "total" => rand(100, 2000),
                    "sub_total" => rand(100, 2000),
                    "tax_amount" => rand(100, 2000),
                    "tax_percentage" => rand(5, 10)
                ],
                [
                    "total" => rand(100, 2000),
                    "sub_total" => rand(100, 2000),
                    "tax_amount" => rand(100, 2000),
                    "tax_percentage" => rand(5, 10)
                ],
            ]),
            'status' => Status::getRandomValue(),
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }


    /**
     * Indicate that the model's that has status of draft.
     *
     * @return Factory
     */
    public function draft(): Factory
    {
        return $this->state(function (array $attributes) {
            return [
                'status' => Status::Drafted,
            ];
        });
    }

    /**
     * Indicate that the model's that has status of draft.
     *
     * @return Factory
     */
    public function potentialAmount(): Factory
    {
        return $this->state(function (array $attributes) {
            return [
                'potential_amount' => $this->faker->randomNumber(4, true),
            ];
        });
    }

    /**
     * Indicate that the model's that has status of Created.
     *
     * @return Factory
     */
    public function drafted(): Factory
    {
        return $this->state(function (array $attributes) {
            return [
                'status' => Status::Drafted,
            ];
        });
    }

    /**
     * Indicate that the model's that has status of sent.
     *
     * @return Factory
     */
    public function sent(): Factory
    {
        return $this->state(function (array $attributes) {
            return [
                'status' => Status::Sent,
            ];
        });
    }

    /**
     * Indicate that the model's that has status of sent.
     *
     * @return Factory
     */
    public function nullified(): Factory
    {
        return $this->state(function (array $attributes) {
            return [
                'status' => Status::Nullified,
                'nullified_at' => now()
            ];
        });
    }

}
