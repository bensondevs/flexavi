<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Traits\FactoryDeletedState;

use App\Models\{ 
    Company, 
    Customer, 
    Appointment, 
    Appointmentable, 
    Worklist, 
    Workday 
};

use App\Enums\Appointment\{
    AppointmentStatus as Status,
    AppointmentType as Type,
    AppointmentCancellationVault as CancellationVault
};

class AppointmentFactory extends Factory
{
    use FactoryDeletedState;

    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Appointment::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $faker = $this->faker;

        $company = Company::inRandomOrder()->first() ?:
            Company::factory()->create();
        $customer = $company->customers()->inRandomOrder()->first() ?:
            Customer::factory()->create(['company_id' => $company->id]);

        $start = $faker->datetime();
        $end = $faker->dateTimeBetween($start, '+10 days');
        return [
            'id' => $faker->uuid(),
            'company_id' => $company->id,
            'customer_id' => $customer->id,

            'start' => $start,
            'end' => $end,

            'status' => Status::Created,
            'type' => rand(Type::Inspection, Type::PaymentReminder),

            'note' => $faker->word(),
        ];
    }

    /**
     * Indicate that the model's belongs to Worklist.
     *
     * @return \Illuminate\Database\Eloquent\Factories\Factory
     */
    public function fromWorklist()
    {
        return $this->afterCreating(function (Appointment $appointment) {
            if (! $appointment->worklists()->count()) {
                $worklist = Worklist::factory()->create();
                $appointment->worklists()->attach($worklist, ['id' => generateUuid(), 'order_index' => 1]);
                $appointment->workdays()->attach($worklist->workday, ['id' => generateUuid()]);
            }
        });
    }

    /**
     * Indicate that the model's has status of Created.
     *
     * @return \Illuminate\Database\Eloquent\Factories\Factory
     */
    public function created()
    {
        return $this->state(function (array $attributes) {
            return [
                'status' => Status::Created,
                'created_at' => now(),
            ];
        });
    }

    /**
     * Indicate that the model's has status of In Process.
     *
     * @return \Illuminate\Database\Eloquent\Factories\Factory
     */
    public function inProcess()
    {
        return $this->state(function (array $attributes) {
            return [
                'status' => Status::InProcess,
                'executed_at' => now(),
            ];
        });
    }

    /**
     * Indicate that the model's has status of Processed.
     *
     * @return \Illuminate\Database\Eloquent\Factories\Factory
     */
    public function processed()
    {
        return $this->state(function (array $attributes) {
            return [
                'status' => Status::Processed,
                'processed_at' => now(),
            ];
        });
    }

    /**
     * Indicate that the model's has status of Calculated.
     *
     * @return \Illuminate\Database\Eloquent\Factories\Factory
     */
    public function calculated()
    {
        return $this->state(function (array $attributes) {
            return [
                'status' => Status::Calculated,
                'calculated_at' => now(),
            ];
        });
    }

    /**
     * Indicate that the model's has status of Cancelled by Roofer.
     *
     * @return \Illuminate\Database\Eloquent\Factories\Factory
     */
    public function cancelledByRoofer()
    {
        return $this->state(function (array $attributes) {
            return [
                'status' => Status::Cancelled,
                'cancellation_cause' => $this->faker->word(),
                'cancellation_vault' => CancellationVault::Roofer,
                'cancellation_note' => $this->faker->word(),
            ];
        });
    }

    /**
     * Indicate that the model's has status of Cancelled by Customer.
     *
     * @return \Illuminate\Database\Eloquent\Factories\Factory
     */
    public function cancelledByCustomer()
    {
        return $this->state(function (array $attributes) {
            return [
                'status' => Status::Cancelled,
                'cancellation_cause' => $this->faker->word(),
                'cancellation_vault' => CancellationVault::Customer,
                'cancellation_note' => $this->faker->word(),
            ];
        });
    }

    /**
     * Indicate that the model's has type of Inspection.
     *
     * @return \Illuminate\Database\Eloquent\Factories\Factory
     */
    public function inspection()
    {
        return $this->state(function (array $attributes) {
            return [
                'type' => Type::Inspection,
            ];
        });
    }

    /**
     * Indicate that the model's has type of Quotation.
     *
     * @return \Illuminate\Database\Eloquent\Factories\Factory
     */
    public function quotation()
    {
        return $this->state(function (array $attributes) {
            return [
                'type' => Type::Quotation,
            ];
        });
    }

    /**
     * Indicate that the model's has type of Execute Work.
     *
     * @return \Illuminate\Database\Eloquent\Factories\Factory
     */
    public function executeWork()
    {
        return $this->state(function (array $attributes) {
            return [
                'type' => Type::ExecuteWork,
            ];
        });
    }

    /**
     * Indicate that the model's has type of Warranty.
     *
     * @return \Illuminate\Database\Eloquent\Factories\Factory
     */
    public function warranty()
    {
        return $this->state(function (array $attributes) {
            return [
                'type' => Type::Warranty,
            ];
        });
    }

    /**
     * Indicate that the model's has type of Payment Pickup.
     *
     * @return \Illuminate\Database\Eloquent\Factories\Factory
     */
    public function paymentPickup()
    {
        return $this->state(function (array $attributes) {
            return [
                'type' => Type::PaymentPickup,
            ];
        });
    }

    /**
     * Indicate that the model's has type of Payment Reminder.
     *
     * @return \Illuminate\Database\Eloquent\Factories\Factory
     */
    public function paymentReminder()
    {
        return $this->state(function (array $attributes) {
            return [
                'type' => Type::PaymentReminder,
            ];
        });
    }
}
