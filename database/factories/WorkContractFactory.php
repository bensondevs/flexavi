<?php

namespace Database\Factories;

use App\Enums\WorkContract\WorkContractStatus;
use App\Models\Company\Company;
use App\Models\Customer\Customer;
use App\Models\WorkContract\WorkContract;
use Illuminate\Database\Eloquent\Factories\Factory;

class WorkContractFactory extends Factory
{
    protected $model = WorkContract::class;

    /**
     * Configure the model factory.
     *
     * @return $this
     */
    public function configure(): static
    {
        return $this->afterMaking(function (WorkContract $workContract) {
            if (!$workContract->company_id) {
                $company = Company::factory()->create();
                $workContract->company()->associate($company);
            }
            if (!$workContract->customer_id) {
                $customer = Customer::factory()->create();
                $workContract->customer()->associate($customer);
            }
        });
    }


    public function definition(): array
    {
        return [
            'id' => generateUuid(),
            'footer' => $this->faker->text,
            'number' => $this->faker->unique()->randomNumber(8),
            'amount' => $this->faker->randomFloat(2, 0, 1000),
            'discount_amount' => $this->faker->randomFloat(2, 0, 100),
            'potential_amount' => $this->faker->randomFloat(2, 0, 100),
            'total_amount' => $this->faker->randomFloat(2, 0, 100),
            'status' => $this->faker->randomElement(WorkContractStatus::getValues()),
        ];
    }

    /**
     * Indicate that the model's that draft.
     *
     * @return Factory
     */
    public function drafted(): Factory
    {
        return $this->state(function (array $attributes) {
            return [
                'status' => WorkContractStatus::Drafted,
            ];
        });
    }

    /**
     * Indicate that the model's that sent.
     *
     * @return Factory
     */
    public function sent(): Factory
    {
        return $this->state(function (array $attributes) {
            return [
                'status' => WorkContractStatus::Sent,
                'send_at' => now(),
            ];
        });
    }

    /**
     * Indicate that the model's that nullified.
     *
     * @return Factory
     */
    public function nullified(): Factory
    {
        return $this->state(function (array $attributes) {
            return [
                'status' => WorkContractStatus::Nullified,
                'nullified_at' => now(),
            ];
        });
    }

    /**
     * Indicate that the model's that signed.
     *
     * @return Factory
     */
    public function signed(): Factory
    {
        return $this->state(function (array $attributes) {
            return [
                'status' => WorkContractStatus::Signed,
                'signed_at' => now(),
            ];
        });
    }
}
