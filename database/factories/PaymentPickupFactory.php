<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Traits\FactoryDeletedState;

use App\Models\{ PaymentPickup, Company, Employee, Appointment };

class PaymentPickupFactory extends Factory
{
    use FactoryDeletedState;

    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = PaymentPickup::class;

    /**
     * Configure the model factory.
     *
     * @return $this
     */
    public function configure()
    {
        return $this->afterMaking(function (PaymentPickup $pickup) {
            if (! $pickup->company_id) {
                $company = Company::inRandomOrder()->first();
                $pickup->company_id = $company->id;
            }

            if (! $pickup->appointment_id) {
                $company = Company::findOrFail($pickup->company_id);
                $appointment = Appointment::factory()->for($company)->create();
                $pickup->appointment_id = $appointment->id;
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
        $shouldPickupAmount = $faker->randomNumber(3, false);
        $pickuedUpAmount = $faker->numberBetween(1, $shouldPickupAmount);

        $structure = [
            'should_pickup_amount' => $shouldPickupAmount,
            'picked_up_amount' => $pickuedUpAmount,
            'should_picked_up_at' => $faker->dateTime(),
        ];

        if ($shouldPickupAmount !== $pickuedUpAmount) {
            $structure['reason_not_all'] = $faker->word();
        }

        return $structure;
    }

    /**
     * Indicate model payment pickup has been picked up.
     *
     * @return \Illuminate\Database\Eloquent\Factories\Factory
     */
    public function pickedUp()
    {
        $faker = $this->faker;
        return $this->state(function (array $attributes) use ($faker) {
            return ['picked_up_at' => $faker->dateTime()];
        });
    }
}