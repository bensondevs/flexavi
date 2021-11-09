<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Traits\FactoryDeletedState;

use App\Models\{ SubAppointment, Appointment, Company };

use App\Enums\SubAppointment\{
    SubAppointmentCancellationVault as CancellationVault,
    SubAppointmentStatus as Status
};

class SubAppointmentFactory extends Factory
{
    use FactoryDeletedState;

    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = SubAppointment::class;

    /**
     * Configure the model factory.
     *
     * @return $this
     */
    public function configure()
    {
        return $this->afterMaking(function (SubAppointment $subAppointment) {
            if (! $subAppointment->company_id) {
                $company = Company::factory()->create();
                $subAppointment->company()->associate($company);
            }

            if (! $subAppointment->appointment_id) {
                $appointment = Appointment::factory()->created()->create();
                $subAppointment->appointment()->associate($appointment);
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
            'status' => Status::Created,
            'start' => $faker->datetime(),
            'end' => $faker->datetime(),
        ];

    }

    /**
     * Indicate that the model's has status of created.
     *
     * @return \Illuminate\Database\Eloquent\Factories\Factory
     */
    public function created()
    {
        $faker = $this->faker;
        return $this->state(function (array $attributes) use ($faker) {
            return [
                'status' => Status::Created,
                'created_at' => $faker->datetime(),
            ];
        });
    }

    /**
     * Indicate that the model's has status of in process.
     *
     * @return \Illuminate\Database\Eloquent\Factories\Factory
     */
    public function inProcess()
    {
        $faker = $this->faker;
        return $this->state(function (array $attributes) use ($faker) {
            return [
                'status' => Status::InProcess,
                'created_at' => $faker->datetime(),
                'in_process_at' => $faker->datetime(),
            ];
        });
    }

    /**
     * Indicate that the model's has status of processed.
     *
     * @return \Illuminate\Database\Eloquent\Factories\Factory
     */
    public function processed()
    {
        $faker = $this->faker;
        return $this->state(function (array $attributes) use ($faker) {
            return [
                'status' => Status::Processed,
                'created_at' => $faker->datetime(),
                'in_process_at' => $faker->datetime(),
                'processed_at' => $faker->datetime(), 
            ];
        });
    }

    /**
     * Indicate that the model's has status of cancelled by roofer.
     *
     * @return \Illuminate\Database\Eloquent\Factories\Factory
     */
    public function cancelledByRoofer()
    {
        $faker = $this->faker;
        return $this->state(function (array $attributes) use ($faker) {
            return [
                'status' => Status::Cancelled,

                'cancellation_cause' => $faker->word(),
                'cancellation_vault' => CancellationVault::Roofer,
                'cancellation_note' => $faker->word(),

                'created_at' => $faker->datetime(),
                'in_process_at' => $faker->datetime(),
                'processed_at' => $faker->datetime(), 
                'cancelled_at' => $faker->datetime(),
            ];
        });
    }

    /**
     * Indicate that the model's has status of cancelled by customer.
     *
     * @return \Illuminate\Database\Eloquent\Factories\Factory
     */
    public function cancelledByCustomer()
    {
        $faker = $this->faker;
        return $this->state(function (array $attributes) use ($faker) {
            return [
                'status' => Status::Cancelled,

                'cancellation_cause' => $faker->word(),
                'cancellation_vault' => CancellationVault::Customer,
                'cancellation_note' => $faker->word(),

                'created_at' => $faker->datetime(),
                'in_process_at' => $faker->datetime(),
                'processed_at' => $faker->datetime(),
                'cancelled_at' => $faker->datetime(),
            ];
        });
    }
}