<?php

namespace Database\Factories;

use App\Enums\Appointment\{AppointmentCancellationVault as CancellationVault,
    AppointmentStatus as Status,
    AppointmentType as Type};
use App\Models\{Appointment\Appointment, Company\Company, Customer\Customer, Worklist\Worklist};
use App\Traits\FactoryDeletedState;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\Factory;

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
     * Configure the model factory.
     *
     * @return $this
     */
    public function configure()
    {
        return $this->afterMaking(function (Appointment $appointment) {
            if (!$appointment->company_id) {
                $company = Company::factory()->create();
                $appointment->company()->associate($company);
            }

            if (!$appointment->customer_id) {
                $customer = Customer::factory()
                    ->for($appointment->company)
                    ->create();
                $appointment->customer()->associate($customer);
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
        $start = $this->faker->dateTimeThisYear()->format('Y-m-d H:i:s');
        $end = Carbon::createFromFormat('Y-m-d H:i:s', $start)->addDays(rand(2, 7))->format('Y-m-d H:i:s');
        return [
            'id' => generateUuid(),

            'start' => $start,
            'end' => $end,
            'status' => Status::Created,
            'type' => $this->faker->randomElement([
                Type::Inspection,
                Type::PaymentReminder,
            ]),
            'note' => $this->faker->word,
        ];
    }

    /**
     * Indicate that the model's belongs to Worklist.
     *
     * @return Factory
     */
    public function fromWorklist()
    {
        return $this->afterCreating(function (Appointment $appointment) {
            if (!$appointment->worklists()->count()) {
                $worklist = Worklist::factory()->create();
                $appointment->worklists()->attach($worklist, [
                    'id' => generateUuid(),
                    'order_index' => 1,
                ]);
                $appointment
                    ->workdays()
                    ->attach($worklist->workday, ['id' => generateUuid()]);
            }
        });
    }

    /**
     * Indicate that the model's has status of Created.
     *
     * @return Factory
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
     * @return Factory
     */
    public function inProcess()
    {
        return $this->state(function (array $attributes) {
            return [
                'status' => Status::InProcess,
                'in_process_at' => now(),
            ];
        });
    }

    /**
     * Indicate that the model's has status of Processed.
     *
     * @return Factory
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
     * @return Factory
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
     * @return Factory
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
     * @return Factory
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
     * @return Factory
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
     * @return Factory
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
     * @return Factory
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
     * @return Factory
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
     * @return Factory
     */
    public function paymentPickup()
    {
        return $this->state(function (array $attributes) {
            return [
                'type' => Type::PaymentPickUp,
            ];
        });
    }

    /**
     * Indicate that the model's has type of Payment Reminder.
     *
     * @return Factory
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
