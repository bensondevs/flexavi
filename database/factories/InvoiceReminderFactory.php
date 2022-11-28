<?php

namespace Database\Factories;

use App\Models\Invoice\Invoice;
use App\Models\Invoice\InvoiceReminder;
use Illuminate\Database\Eloquent\Factories\Factory;

class InvoiceReminderFactory extends Factory
{

    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = InvoiceReminder::class;

    /**
     * Configure the model factory.
     *
     * @return $this
     */
    public function configure(): static
    {
        return $this->afterMaking(function (InvoiceReminder $reminder) {
            if (!$reminder->invoice_id) {
                $invoice = Invoice::factory()->create([]);
                $reminder->invoice()->associate($invoice);
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
        return [];
    }

    /**
     * Indicate that the invoice reminder is first reminder.
     *
     * @return Factory
     */
    public function firstReminder(): Factory
    {
        return $this->state(function (array $attributes) {
            return [
                'first_reminder_at' => now()->subDays(rand(1, 5)),
            ];
        });
    }

    /**
     * Indicate that the invoice reminder is first reminder sent.
     *
     * @return Factory
     */
    public function firstReminderSent(): Factory
    {
        return $this->state(function (array $attributes) {
            return [
                'first_reminder_at' => now()->subDays(rand(1, 5)),
                'customer_first_reminder_sent_at' => now()->subDays(rand(1, 5)),
                'user_first_reminder_sent_at' => now()->subDays(rand(1, 5)),
            ];
        });
    }

    /**
     * Indicate that the invoice reminder is second reminder.
     *
     * @return Factory
     */
    public function secondReminder(): Factory
    {
        return $this->state(function (array $attributes) {
            return [
                'second_reminder_at' => now()->subDays(rand(1, 5)),
            ];
        });
    }

    /**
     * Indicate that the invoice reminder is second reminder sent.
     *
     * @return Factory
     */
    public function secondReminderSent(): Factory
    {
        return $this->state(function (array $attributes) {
            return [
                'second_reminder_at' => now()->subDays(rand(1, 5)),
                'customer_second_reminder_sent_at' => now()->subDays(rand(1, 5)),
                'user_second_reminder_sent_at' => now()->subDays(rand(1, 5)),
            ];
        });
    }

    /**
     * Indicate that the invoice reminder is third reminder.
     *
     * @return Factory
     */
    public function thirdReminder(): Factory
    {
        return $this->state(function (array $attributes) {
            return [
                'third_reminder_at' => now()->subDays(rand(1, 5)),
            ];
        });
    }

    /**
     * Indicate that the invoice reminder is third reminder sent.
     *
     * @return Factory
     */
    public function thirdReminderSent(): Factory
    {
        return $this->state(function (array $attributes) {
            return [
                'third_reminder_at' => now()->subDays(rand(1, 5)),
                'user_third_reminder_sent_at' => now()->subDays(rand(1, 5)),
                'customer_third_reminder_sent_at' => now()->subDays(rand(1, 5)),
            ];
        });
    }

    /**
     * Indicate that the invoice reminder is debt collector sent.
     *
     * @return Factory
     */
    public function debtCollectorSent(): Factory
    {
        return $this->state(function (array $attributes) {
            return [
                'sent_to_debt_collector_at' => now()->subDays(rand(1, 5)),
            ];
        });
    }

    /**
     * Indicate that the invoice reminder is paid via debt collector .
     *
     * @return Factory
     */
    public function paidViaDebtCollector(): Factory
    {
        return $this->state(function (array $attributes) {
            return [
                'sent_to_debt_collector_at' => now()->subDays(rand(1, 5)),
                'paid_via_debt_collector_at' => now()->subDays(rand(1, 5)),
            ];
        });
    }
}
