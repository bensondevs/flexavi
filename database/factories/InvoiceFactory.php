<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

use App\Models\{ Invoice, Company, Customer };

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
                $invoice->company()->associate(Company::factory()->create());
            }

            if (! $invoice->customer_id) {
                $invoice->customer()->associate(Customer::factory()->create([
                    'company_id' => $invoice->company_id
                ]));
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
     * Indicate that the model's has status of first reminder.
     *
     * @return \Illuminate\Database\Eloquent\Factories\Factory
     */
    public function firstReminder()
    {
        //
    }

    /**
     * Indicate that the model's has status of first reminder sent.
     *
     * @return \Illuminate\Database\Eloquent\Factories\Factory
     */
    public function firstReminderSent()
    {
        //
    }

    /**
     * Indicate that the model's has status of second reminder.
     *
     * @return \Illuminate\Database\Eloquent\Factories\Factory
     */
    public function secondReminder()
    {
        //
    }

    /**
     * Indicate that the model's has status of second reminder sent.
     *
     * @return \Illuminate\Database\Eloquent\Factories\Factory
     */
    public function secondReminderSent()
    {
        //
    }

    /**
     * Indicate that the model's has status of third reminder.
     *
     * @return \Illuminate\Database\Eloquent\Factories\Factory
     */
    public function thirdReminder()
    {
        //
    }

    /**
     * Indicate that the model's has status of third reminder sent.
     *
     * @return \Illuminate\Database\Eloquent\Factories\Factory
     */
    public function thirdReminderSent()
    {
        //
    }

    /**
     * Indicate that the model's has status of debt collector sent.
     *
     * @return \Illuminate\Database\Eloquent\Factories\Factory
     */
    public function debtCollectorSent()
    {
        //
    }

    /**
     * Indicate that the model's has status of paid via debt collector.
     *
     * @return \Illuminate\Database\Eloquent\Factories\Factory
     */
    public function paidViaDebtCollector()
    {
        //
    }
}
