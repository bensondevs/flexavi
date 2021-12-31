<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

use App\Models\{ Invoice, Company, Customer, Appointment };

use App\Enums\Invoice\{
    InvoiceStatus as Status,
    InvoicePaymentMethod as PaymentMethod
};

class InvoiceFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Invoice::class;

    /**
     * Configure the model factory.
     *
     * @return $this
     */
    public function configure()
    {
        return $this->afterMaking(function (Invoice $invoice) {
            if (! $invoice->company_id) {
                $company = Company::factory()->create();
                $invoice->company()->associate($company);
            }


            if (! $invoice->customer_id) {
                $company = Company::findOrFail($invoice->company_id);
                $customer = Customer::factory()->for($company)->create();
                $invoice->customer()->associate($customer);
            }

            if (! $invoice->invoiceable_id) {
                $company = Company::findOrFail($invoice->company_id);
                $customer = Customer::findOrFail($invoice->customer_id);
                $invoiceable = Appointment::factory()
                    ->for($company)
                    ->for($customer)
                    ->create();
                $invoice->fill([
                    'invoiceable_type' => Appointment::class,
                    'invoiceable_id' => $invoiceable->id,
                ]);
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
            'id' => generateUuid(),

            'invoice_number' => $faker->randomNumber(5, true),

            'total' => $faker->randomNumber(4, false),
            'total_paid' => $faker->randomNumber(3, false),
            'total_in_terms' => $faker->randomNumber(3, false),

            'status' => rand(Status::Created, Status::PaidViaDebtCollector),
            'payment_method' => rand(PaymentMethod::Cash, PaymentMethod::BankTransfer),
        ];
    }

    /**
     * Indicate that the model's has status of Created.
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
     * Indicate that the model's has status of Sent.
     *
     * @return \Illuminate\Database\Eloquent\Factories\Factory
     */
    public function sent()
    {
        $faker = $this->faker;
        return $this->state(function (array $attributes) use ($faker) {
            return [
                'status' => Status::Sent,
                'created_at' => $faker->datetime(),
                'sent_at' => $faker->datetime(),
            ];
        });
    }

    /**
     * Indicate that the model's has status of paid.
     *
     * @return \Illuminate\Database\Eloquent\Factories\Factory
     */
    public function paid()
    {
        $faker = $this->faker;
        return $this->state(function (array $attributes) use ($faker) {
            return [
                'status' => Status::Paid,
                'created_at' => $faker->datetime(),
                'sent_at' => $faker->datetime(),
                'paid_at' => $faker->datetime(),
            ];
        });
    }

    /**
     * Indicate that the model's has status of payment overdue.
     *
     * @return \Illuminate\Database\Eloquent\Factories\Factory
     */
    public function paymentOverdue()
    {
        $faker = $this->faker;
        return $this->state(function (array $attributes) use ($faker) {
            return [
                'status' => Status::PaymentOverdue,
                'created_at' => $faker->datetime(),
                'sent_at' => $faker->datetime(),
                'paid_at' => $faker->datetime(),
                'payment_overdue_at' => $faker->datetime(),
            ];
        });
    }

    /**
     * Indicate that the model's has status of first reminder sent.
     *
     * @return \Illuminate\Database\Eloquent\Factories\Factory
     */
    public function firstReminderSent()
    {
        $faker = $this->faker;
        return $this->state(function (array $attributes) use ($faker) {
            return [
                'status' => Status::FirstReminderSent,
                'created_at' => $faker->datetime(),
                'sent_at' => $faker->datetime(),
                'paid_at' => $faker->datetime(),
                'payment_overdue_at' => $faker->datetime(),
                'first_reminder_sent_at' => $faker->datetime(),
                'first_reminder_overdue_at' => $faker->datetime(),
            ];
        });
    }

    /**
     * Indicate that the model's has status of first reminder sent.
     *
     * @return \Illuminate\Database\Eloquent\Factories\Factory
     */
    public function firstReminderOverdue()
    {
        $faker = $this->faker;
        return $this->state(function (array $attributes) use ($faker) {
            return [
                'status' => Status::FirstReminderOverdue,
                'created_at' => $faker->datetime(),
                'sent_at' => $faker->datetime(),
                'paid_at' => $faker->datetime(),
                'payment_overdue_at' => $faker->datetime(),
                'first_reminder_sent_at' => $faker->datetime(),
                'first_reminder_overdue_at' => $faker->datetime(),
            ];
        });
    }

    /**
     * Indicate that the model's has status of second reminder sent.
     *
     * @return \Illuminate\Database\Eloquent\Factories\Factory
     */
    public function secondReminderSent()
    {
        $faker = $this->faker;
        return $this->state(function (array $attributes) use ($faker) {
            return [
                'status' => Status::SecondReminderSent,
                'created_at' => $faker->datetime(),
                'sent_at' => $faker->datetime(),
                'paid_at' => $faker->datetime(),
                'payment_overdue_at' => $faker->datetime(),
                'first_reminder_sent_at' => $faker->datetime(),
                'first_reminder_overdue_at' => $faker->datetime(),
                'second_reminder_sent_at' => $faker->datetime(),
                'second_reminder_overdue_at' => $faker->datetime(),
            ];
        });
    }

    /**
     * Indicate that the model's has status of second reminder overdue.
     *
     * @return \Illuminate\Database\Eloquent\Factories\Factory
     */
    public function secondReminderOverdue()
    {
        $faker = $this->faker;
        return $this->state(function (array $attributes) use ($faker) {
            return [
                'status' => Status::SecondReminderOverdue,
                'created_at' => $faker->datetime(),
                'sent_at' => $faker->datetime(),
                'paid_at' => $faker->datetime(),
                'payment_overdue_at' => $faker->datetime(),
                'first_reminder_sent_at' => $faker->datetime(),
                'first_reminder_overdue_at' => $faker->datetime(),
                'second_reminder_sent_at' => $faker->datetime(),
                'second_reminder_overdue_at' => $faker->datetime(),
            ];
        });
    }

    /**
     * Indicate that the model's has status of third reminder.
     *
     * @return \Illuminate\Database\Eloquent\Factories\Factory
     */
    public function thirdReminder()
    {
        $faker = $this->faker;
        return $this->state(function (array $attributes) use ($faker) {
            return [
                'status' => Status::ThirdReminderSent,
                'created_at' => $faker->datetime(),
                'sent_at' => $faker->datetime(),
                'paid_at' => $faker->datetime(),
                'payment_overdue_at' => $faker->datetime(),
                'first_reminder_sent_at' => $faker->datetime(),
                'first_reminder_overdue_at' => $faker->datetime(),
                'second_reminder_sent_at' => $faker->datetime(),
                'second_reminder_overdue_at' => $faker->datetime(),
                'third_reminder_sent_at' => $faker->datetime(),
                'third_reminder_overdue_at' => $faker->datetime(),
            ];
        });
    }

    /**
     * Indicate that the model's has status of third reminder sent.
     *
     * @return \Illuminate\Database\Eloquent\Factories\Factory
     */
    public function thirdReminderSent()
    {
        $faker = $this->faker;
        return $this->state(function (array $attributes) use ($faker) {
            return [
                'status' => Status::ThirdReminderOverdue,
                'created_at' => $faker->datetime(),
                'sent_at' => $faker->datetime(),
                'paid_at' => $faker->datetime(),
                'payment_overdue_at' => $faker->datetime(),
                'first_reminder_sent_at' => $faker->datetime(),
                'first_reminder_overdue_at' => $faker->datetime(),
                'second_reminder_sent_at' => $faker->datetime(),
                'second_reminder_overdue_at' => $faker->datetime(),
                'third_reminder_sent_at' => $faker->datetime(),
                'third_reminder_overdue_at' => $faker->datetime(),
            ];
        });
    }

    /**
     * Indicate that the model's has status of debt collector sent.
     *
     * @return \Illuminate\Database\Eloquent\Factories\Factory
     */
    public function debtCollectorSent()
    {
        $faker = $this->faker;
        return $this->state(function (array $attributes) use ($faker) {
            return [
                'status' => Status::DebtCollectorSent,
                'created_at' => $faker->datetime(),
                'sent_at' => $faker->datetime(),
                'paid_at' => $faker->datetime(),
                'payment_overdue_at' => $faker->datetime(),
                'first_reminder_sent_at' => $faker->datetime(),
                'first_reminder_overdue_at' => $faker->datetime(),
                'second_reminder_sent_at' => $faker->datetime(),
                'second_reminder_overdue_at' => $faker->datetime(),
                'third_reminder_sent_at' => $faker->datetime(),
                'third_reminder_overdue_at' => $faker->datetime(),
                'debt_collector_sent_at' => $faker->datetime(),
                'debt_collector_overdue_at' => $faker->datetime(),
            ];
        });
    }

    /**
     * Indicate that the model's has status of paid via debt collector.
     *
     * @return \Illuminate\Database\Eloquent\Factories\Factory
     */
    public function paidViaDebtCollector()
    {
        $faker = $this->faker;
        return $this->state(function (array $attributes) use ($faker) {
            return [
                'status' => Status::PaidViaDebtCollector,
                'created_at' => $faker->datetime(),
                'sent_at' => $faker->datetime(),
                'paid_at' => $faker->datetime(),
                'payment_overdue_at' => $faker->datetime(),
                'first_reminder_sent_at' => $faker->datetime(),
                'first_reminder_overdue_at' => $faker->datetime(),
                'second_reminder_sent_at' => $faker->datetime(),
                'second_reminder_overdue_at' => $faker->datetime(),
                'third_reminder_sent_at' => $faker->datetime(),
                'third_reminder_overdue_at' => $faker->datetime(),
                'debt_collector_sent_at' => $faker->datetime(),
                'debt_collector_overdue_at' => $faker->datetime(),
                'paid_via_debt_collector_at' => $faker->datetime(),
            ];
        });
    }

    /**
     * Indicate that the model is attached to appointment.
     *
     * @param  \App\Models\Appointment  $appointment
     * @return \Illuminate\Database\Eloquent\Factories\Factory
     */
    public function appointmentInvoiceable(Appointment $appointment)
    {
        return $this->state(function (array $attributes) use ($appointment) {
            return [
                'invoiceable_type' => Appointment::class,
                'invoiceable_id' => $appointment->id,
            ];
        });
    }

    /**
     * Indicate that the model is attached to quotation.
     *
     * @param  \App\Models\Quotation  $quotation
     * @return \Illuminate\Database\Eloquent\Factories\Factory
     */
    public function quotationInvoiceable(Quotation $quotation)
    {
        return $this->state(function (array $attributes) use ($quotation) {
            return [
                'invoiceable_type' => Quotation::class,
                'invoiceable_id' => $quotation->id,
            ];
        });
    }

    /**
     * Indicate that the model is attached to work contract.
     *
     * @param  \App\Models\WorkContract  $contract
     * @return \Illuminate\Database\Eloquent\Factories\Factory
     */
    public function workContractInvoiceable(WorkContract $contract)
    {
        return $this->state(function (array $attributes) use ($contract) {
            return [
                'invoiceable_type' => WorkContract::class,
                'invoiceable_id' => $contract->id,
            ];
        });
    }
}