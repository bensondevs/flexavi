<?php

namespace Tests\Feature\Dashboard\Company\Invoice;

use App\Enums\Invoice\InvoiceReminderSentType;
use App\Models\Customer\Customer;
use App\Models\Invoice\Invoice;
use App\Models\Invoice\InvoiceSetting;
use App\Models\User\User;
use Illuminate\Testing\Fluent\AssertableJson;
use Tests\TestCase;

class InvoiceSettingTest extends TestCase
{
    /**
     * Test view invoice setting
     *
     * @return void
     */
    public function test_view_invoice_setting(): void
    {
        $this->actingAs($user = User::factory()->owner()->create());
        $company = $user->owner->company;

        $customer = Customer::factory()->for($company)->create();
        $invoice = Invoice::factory()->for($company)->for($customer)->create();

        $setting = InvoiceSetting::factory()->for($invoice)->create();

        $response = $this->getJson(urlWithParams('/api/dashboard/companies/invoices/settings', [
            'invoice_id' => $invoice->id,
        ]));

        $response->assertOk();

        $response->assertJson(function (AssertableJson $json) {
            $json->has('invoice_setting');
        });
    }


    /**
     * Test update invoice setting
     *
     * @return void
     */
    public function test_update_invoice_setting(): void
    {
        $this->actingAs($user = User::factory()->owner()->create());
        $company = $user->owner->company;
        $customer = Customer::factory()->for($company)->create();
        $invoice = Invoice::factory()->for($company)->for($customer)->create();
        InvoiceSetting::factory()->for($invoice)->create();

        $data = [
            'invoice_id' => $invoice->id,
            'auto_reminder_activated' => true,
            'first_reminder_type' => InvoiceReminderSentType::getRandomValue(),
            'second_reminder_days' => 2,
            'second_reminder_type' => InvoiceReminderSentType::getRandomValue(),
            'third_reminder_days' => 3,
            'third_reminder_type' => InvoiceReminderSentType::getRandomValue(),
            'debt_collector_reminder_days' => 4,
            'debt_collector_reminder_type' => InvoiceReminderSentType::getRandomValue(),
        ];

        $response = $this->postJson('/api/dashboard/companies/invoices/settings/save', $data);

        $response->assertCreated();

        $response->assertJson(function (AssertableJson $json) {
            $json->where('status', 'success');
            $json->has('message');

            $json->has('invoice_setting');
        });

        $this->assertDatabaseHas((new InvoiceSetting())->getTable(), $data);
    }

}
