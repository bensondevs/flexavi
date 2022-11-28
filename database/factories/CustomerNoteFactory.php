<?php

namespace Database\Factories;

use App\Models\Customer\Customer;
use App\Models\Customer\CustomerNote;
use App\Traits\FactoryDeletedState;
use Illuminate\Database\Eloquent\Factories\Factory;

class CustomerNoteFactory extends Factory
{
    use FactoryDeletedState;

    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = CustomerNote::class;

    /**
     * Configure the model factory.
     *
     * @return $this
     */
    public function configure()
    {
        return $this->afterMaking(function (CustomerNote $customerNote) {
            if (!$customerNote->customer_id) {
                $customer = Customer::factory()->create();
                $customerNote->customer()->associate($customer);
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
            'note' => $this->faker->word(),
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }
}
