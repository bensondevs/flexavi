<?php

namespace Database\Factories;

use App\Enums\Invoice\{InvoicePaymentMethod as PaymentMethod, InvoiceStatus as Status};
use App\Models\{Company\Company, Customer\Customer, Invoice\Invoice};
use App\Traits\FactoryDeletedState;
use Illuminate\Database\Eloquent\Factories\Factory;

class InvoiceFactory extends Factory
{
    use FactoryDeletedState;

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
    public function configure(): static
    {
        return $this->afterMaking(function (Invoice $invoice) {
            if (!$invoice->company_id) {
                $company = Company::factory()->create();
                $invoice->company()->associate($company);
            }
            if (!$invoice->customer_id) {
                $company = Company::findOrFail($invoice->company_id);
                $customer = Customer::factory()
                    ->for($company)
                    ->create();
                $invoice->customer()->associate($customer);
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
            'customer_address' => $this->faker->address,
            'number' => $this->faker->randomNumber(5, true),
            'date' => $this->faker->date,
            'due_date' => $this->faker->date,
            'status' => $this->faker->randomElement([
                Status::Drafted
            ]),
            'payment_method' => $this->faker->randomElement([
                PaymentMethod::Cash,
                PaymentMethod::BankTransfer,
            ]),

            'amount' => $this->faker->randomNumber(6, true),
            'discount_amount' => $this->faker->randomNumber(6, true),
            'total_amount' => $this->faker->randomNumber(6, true),
        ];
    }

    /**
     * Indicate that the model's has status of Created.
     *
     * @return Factory
     */
    public function drafted(): Factory
    {
        return $this->state(function (array $attributes) {
            return [
                'status' => Status::Drafted,
                'created_at' => $this->faker->dateTime,
            ];
        });
    }

    /**
     * Indicate that the model's has status of Created.
     *
     * @return Factory
     */
    public function sent(): Factory
    {
        return $this->state(function (array $attributes) {
            return [
                'status' => Status::Sent,
                'sent_at' => $this->faker->dateTime,
                'created_at' => $this->faker->dateTime,
            ];
        });
    }

    /**
     * Indicate that the model's has status of paid.
     *
     * @return Factory
     */
    public function paid(): Factory
    {
        return $this->state(function (array $attributes) {
            return [
                'status' => Status::Paid,
                'created_at' => $this->faker->dateTime,
                'sent_at' => $this->faker->dateTime,
                'paid_at' => $this->faker->dateTime,
            ];
        });
    }

    /**
     * Indicate that the model's is fixed price.
     *
     * @return Factory
     */
    public function fixedPrice(): Factory
    {
        return $this->state(function (array $attributes) {
            return [
                'status' => Status::Drafted,
                'potential_amount' => $this->faker->randomNumber(6, true)
            ];
        });
    }

    /**
     * Indicate that the model's has status of payment overdue.
     *
     * @return Factory
     */
    public function overdue(): Factory
    {
        return $this->state(function (array $attributes) {
            return [
                'status' => Status::PaymentOverdue,
                'created_at' => $this->faker->dateTime,
                'sent_at' => $this->faker->dateTime,
            ];
        });
    }

    /**
     * Indicate that the model's has status of first reminder sent.
     *
     * @return Factory
     */
    public function firstReminderSent(): Factory
    {
        return $this->state(function (array $attributes) {
            return [
                'status' => Status::FirstReminderSent,
                'created_at' => $this->faker->dateTime,
                'sent_at' => $this->faker->dateTime,
            ];
        });
    }

    /**
     * Indicate that the model's has status of first reminder sent.
     *
     * @return Factory
     */
    public function firstReminderOverdue(): Factory
    {
        return $this->state(function (array $attributes) {
            return [
                'status' => Status::FirstReminderOverdue,
                'created_at' => $this->faker->dateTime,
                'sent_at' => $this->faker->dateTime,
            ];
        });
    }

    /**
     * Indicate that the model's has status of second reminder sent.
     *
     * @return Factory
     */
    public function secondReminderSent(): Factory
    {
        return $this->state(function (array $attributes) {
            return [
                'status' => Status::SecondReminderSent,
                'created_at' => $this->faker->dateTime,
                'sent_at' => $this->faker->dateTime,
            ];
        });
    }

    /**
     * Indicate that the model's has status of second reminder overdue.
     *
     * @return Factory
     */
    public function secondReminderOverdue(): Factory
    {
        return $this->state(function (array $attributes) {
            return [
                'status' => Status::SecondReminderOverdue,
                'created_at' => $this->faker->dateTime,
                'sent_at' => $this->faker->dateTime,
            ];
        });
    }

    /**
     * Indicate that the model's has status of third reminder.
     *
     * @return Factory
     */
    public function thirdReminderSent(): Factory
    {
        return $this->state(function (array $attributes) {
            return [
                'status' => Status::ThirdReminderSent,
                'created_at' => $this->faker->dateTime,
                'sent_at' => $this->faker->dateTime,
            ];
        });
    }

    /**
     * Indicate that the model's has status of third reminder sent.
     *
     * @return Factory
     */
    public function thirdReminderOverdue(): Factory
    {
        return $this->state(function (array $attributes) {
            return [
                'status' => Status::ThirdReminderOverdue,
                'created_at' => $this->faker->dateTime,
                'sent_at' => $this->faker->dateTime,
            ];
        });
    }

    /**
     * Indicate that the model's has status of debt collector sent.
     *
     * @return Factory
     */
    public function debtCollectorSent(): Factory
    {
        return $this->state(function (array $attributes) {
            return [
                'status' => Status::DebtCollectorSent,
                'created_at' => $this->faker->dateTime,
                'sent_at' => $this->faker->dateTime,
            ];
        });
    }

    /**
     * Indicate that the model's has status of paid via debt collector.
     *
     * @return Factory
     */
    public function paidViaDebtCollector(): Factory
    {
        return $this->state(function (array $attributes) {
            return [
                'status' => Status::PaidViaDebtCollector,
                'created_at' => $this->faker->dateTime,
                'sent_at' => $this->faker->dateTime,
                'paid_at' => $this->faker->dateTime,
            ];
        });
    }
}
