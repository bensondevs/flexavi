<?php

namespace Database\Factories;

use App\Enums\Invoice\InvoiceReminderSentType;
use App\Enums\Setting\Invoice\InvoiceSettingKey;
use App\Enums\Setting\SettingModule;
use App\Models\Invoice\Invoice;
use App\Models\Setting\InvoiceSetting;
use App\Models\Setting\Setting;
use Illuminate\Database\Eloquent\Factories\Factory;

class InvoiceSettingFactory extends Factory
{
    protected $model = InvoiceSetting::class;

    /**
     * Configure the model factory.
     *
     * @return $this
     */
    public function configure(): static
    {
        return $this->afterMaking(function (InvoiceSetting $invoiceSetting) {
            if (!$invoiceSetting->invoice_id) {
                $invoice = Invoice::factory()->create();
                $invoiceSetting->invoice()->associate($invoice);
            }
        });
    }

    public function definition(): array
    {
        return [
            'auto_reminder_activated' => $this->faker->boolean(),
            'first_reminder_type' => InvoiceReminderSentType::InHouseUserWithCustomer,
            'first_reminder_days' => Setting::findValueOf([
                'module' => SettingModule::Invoice,
                'key' => InvoiceSettingKey::FirstReminderAfterDueDate
            ]),
            'second_reminder_type' => InvoiceReminderSentType::InHouseUserWithCustomer,
            'second_reminder_days' => Setting::findValueOf([
                'module' => SettingModule::Invoice,
                'key' => InvoiceSettingKey::SecondReminderAfterFirstReminder
            ]),
            'third_reminder_type' => InvoiceReminderSentType::InHouseUserWithCustomer,
            'third_reminder_days' => Setting::findValueOf([
                'module' => SettingModule::Invoice,
                'key' => InvoiceSettingKey::ThirdReminderAfterSecondReminder
            ]),
            'debt_collector_reminder_type' => InvoiceReminderSentType::InHouseUserWithCustomer,
            'debt_collector_reminder_days' => Setting::findValueOf([
                'module' => SettingModule::Invoice,
                'key' => InvoiceSettingKey::SendDebtCollectorAfterThirdReminder
            ]),
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }
}
