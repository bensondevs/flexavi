<?php

namespace Tests\Integration\Dashboard\Company\Invoice;

use App\Enums\Invoice\InvoiceStatus;
use App\Jobs\SendMail;
use App\Mail\Invoice\InvoiceFirstReminder;
use App\Mail\Invoice\InvoiceSecondReminder;
use App\Mail\Invoice\InvoiceThirdReminder;
use App\Models\Invoice\Invoice;
use App\Models\Invoice\InvoiceLog;
use App\Models\Invoice\InvoiceReminder;
use App\Models\Setting\InvoiceSetting;
use App\Models\User\User;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\WithFaker;
use Mail;
use Queue;
use Tests\TestCase;

class SendInvoiceReminderTest extends TestCase
{
    use WithFaker;


    public const BASE_URL = '/api/dashboard/companies/invoices';

    /**
     * Test
     *
     * @return void
     */
    public function test_paid_via_debt_collector_case(): void
    {
        $data = $this->prepareTestingData();

        // Jump to due date
        Carbon::setTestNow($data['invoice']->due_date);
        $this->assertTrue($data['invoice']->isOverdue());
        $this->assertNotNull($data['reminder']->first_reminder_at);

        // Send reminder
        Carbon::setTestNow($data['invoice']->due_date->addDays(rand(1, 5)));
        $this->sendFirstReminder($data['invoice']);
        $data['reminder'] = $data['reminder']->fresh();
        $this->assertNotNull($data['reminder']->customer_first_reminder_sent_at);

        // Assert second reminder will be sent after x days (depends on settings)
        $setting = $data['settings']->fresh();
        $this->assertEquals(
            now()->addDays($setting->second_reminder_days)->format('Y-m-d'),
            $data['reminder']->second_reminder_at->format('Y-m-d')
        );

        // Jump to second reminder date
        Carbon::setTestNow($data['reminder']->second_reminder_at);
        $data['invoice'] = $data['invoice']->fresh();
        $this->sendSecondReminder($data['invoice']);
        $data['reminder'] = $data['reminder']->fresh();
        $this->assertNotNull($data['reminder']->customer_second_reminder_sent_at);

        // Assert third reminder will be sent after x days (depends on settings)
        $setting = $setting->fresh();
        $this->assertEquals(
            now()->addDays($setting->third_reminder_days)->format('Y-m-d'),
            $data['reminder']->third_reminder_at->format('Y-m-d')
        );


        // Jump to third reminder date
        Carbon::setTestNow($data['reminder']->third_reminder_at);
        $data['invoice'] = $data['invoice']->fresh();
        $this->sendThirdReminder($data['invoice']);
        $data['reminder'] = $data['reminder']->fresh();
        $this->assertNotNull($data['reminder']->customer_third_reminder_sent_at);

        // Assert debt collector will be sent after x days (depends on settings)
        $setting = $setting->fresh();
        $this->assertEquals(
            now()->addDays($setting->debt_collector_reminder_days)->format('Y-m-d'),
            $data['reminder']->sent_to_debt_collector_at->format('Y-m-d')
        );


        // Jump to debt collector date
        Carbon::setTestNow($data['reminder']->sent_to_debt_collector_at);
        $data['invoice'] = $data['invoice']->fresh();
        $this->payViaDebtCollector($data['user'], $data['invoice']);
        $data['invoice'] = $data['invoice']->fresh();
        $this->assertTrue($data['invoice']->isPaidViaDebtCollector());
    }

    /**
     * Prepare data
     *
     * @return array
     */
    private function prepareTestingData(): array
    {
        $this->actingAs($user = User::factory()->owner()->create());

        $company = $user->owner->company;

        $invoice = Invoice::factory()
            ->for($company)
            ->sent()
            ->create();

        $reminder = InvoiceReminder::factory()
            ->for($invoice)
            ->create();

        $invoice->due_date = now()->addDays(rand(1, 3));
        $invoice->saveQuietly();

        $setting = InvoiceSetting::factory()->for($invoice)->create();

        return [
            'company' => $company,
            'invoice' => $invoice,
            'reminder' => $reminder,
            'user' => $user,
            'setting' => $setting,
        ];
    }


    private function sendFirstReminder(Invoice $invoice)
    {
        Queue::fake();
        Mail::fake();
        $response = $this->patchJson(self::BASE_URL . "/change_status", [
            'status' => InvoiceStatus::FirstReminderSent,
            'invoice_id' => $invoice->id,
        ]);

        $response->assertSuccessful();

        Queue::assertPushed(SendMail::class, function ($job) {
            return $job->mailable instanceof InvoiceFirstReminder;
        });
    }

    private function sendSecondReminder(Invoice $invoice)
    {
        Queue::fake();
        Mail::fake();
        $response = $this->patchJson(self::BASE_URL . "/change_status", [
            'status' => InvoiceStatus::SecondReminderSent,
            'invoice_id' => $invoice->id,
        ]);

        $response->assertSuccessful();

        Queue::assertPushed(SendMail::class, function ($job) {
            return $job->mailable instanceof InvoiceSecondReminder;
        });
    }


    public function sendThirdReminder(Invoice $invoice)
    {
        Queue::fake();
        Mail::fake();

        $response = $this->patchJson(self::BASE_URL . "/change_status", [
            'status' => InvoiceStatus::ThirdReminderSent,
            'invoice_id' => $invoice->id,
        ]);

        $response->assertSuccessful();

        Queue::assertPushed(SendMail::class, function ($job) {
            return $job->mailable instanceof InvoiceThirdReminder;
        });
    }


    public function payViaDebtCollector(User $user, Invoice $invoice)
    {
        Queue::fake();
        Mail::fake();

        $response = $this->patchJson(self::BASE_URL . "/change_status", [
            'status' => InvoiceStatus::PaidViaDebtCollector,
            'invoice_id' => $invoice->id,
        ]);

        $response->assertSuccessful();
        Queue::assertPushed(SendMail::class);

        $this->assertDatabaseHas((new InvoiceLog())->getTable(), [
            'invoice_id' => $invoice->id,
            'message' => $user->fullname . ' has updated invoice data',
        ]);
    }
}
