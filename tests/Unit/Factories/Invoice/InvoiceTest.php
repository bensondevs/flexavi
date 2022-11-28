<?php

namespace Tests\Unit\Factories\Invoice;

use App\Enums\Invoice\InvoiceStatus;
use App\Jobs\Invoice\ChangeOverdueInvoiceStatus;
use App\Jobs\Invoice\CheckInvoiceReminder;
use App\Jobs\Invoice\CheckOverdueInvoices;
use App\Jobs\Invoice\SendInvoiceFirstReminder;
use App\Jobs\Invoice\SendInvoiceSecondReminder;
use App\Jobs\Invoice\SendInvoiceThirdReminder;
use App\Models\Invoice\Invoice;
use Illuminate\Support\Facades\Queue;
use Tests\TestCase;

class InvoiceTest extends TestCase
{
    /**
     * Test scope invoice need reminder
     *
     * @return void
     */
    public function test_create_company_invoice_need_reminder_instance()
    {
        // make an instance
        $invoice = Invoice::factory()->needReminder()->create();

        // assert the instance
        $this->assertNotNull($invoice);
        $this->assertModelExists($invoice);
        $this->assertDatabaseHas('invoices', [
            'customer_address' => $invoice->customer_address,
            'invoice_number' => $invoice->invoice_number,
            'date' => $invoice->date,
            'expiry_date' => $invoice->expiry_date,
            'status' => $invoice->status,
            'payment_method' => $invoice->payment_method,
            'amount' => $invoice->amount,
            'vat_percentage' => $invoice->vat_percentage,
            'discount_amount' => $invoice->discount_amount,
            'total_paid' => $invoice->total_paid,
            'total_amount' => $invoice->total_amount,
            'payment_overdue_at' => $invoice->payment_overdue_at,
            'first_reminder_overdue_at' => $invoice->first_reminder_overdue_at,
            'second_reminder_overdue_at' => $invoice->second_reminder_overdue_at,
            'third_reminder_overdue_at' => $invoice->third_reminder_overdue_at,
            'debt_collector_overdue_at' => $invoice->debt_collector_overdue_at
        ]);

        // assert the model relations
        $this->assertNotNull($invoice->company);
        $this->assertNotNull($invoice->customer);

        // assert check scope need reminder
        $this->assertGreaterThanOrEqual(InvoiceStatus::Overdue, $invoice->status);
        $this->assertLessThanOrEqual(InvoiceStatus::ThirdReminderOverdue, $invoice->status);
    }

    /**
     * Test scope unpaid invoice
     *
     * @return void
     */
    public function test_create_company_invoice_unpaid_instance()
    {
        // make an instance
        $invoice = Invoice::factory()->unpaid()->create();

        // assert the instance
        $this->assertNotNull($invoice);
        $this->assertModelExists($invoice);
        $this->assertDatabaseHas('invoices', [
            'customer_address' => $invoice->customer_address,
            'invoice_number' => $invoice->invoice_number,
            'date' => $invoice->date,
            'expiry_date' => $invoice->expiry_date,
            'status' => $invoice->status,
            'payment_method' => $invoice->payment_method,
            'amount' => $invoice->amount,
            'vat_percentage' => $invoice->vat_percentage,
            'discount_amount' => $invoice->discount_amount,
            'total_paid' => $invoice->total_paid,
            'total_amount' => $invoice->total_amount,
            'payment_overdue_at' => $invoice->payment_overdue_at,
            'first_reminder_overdue_at' => $invoice->first_reminder_overdue_at,
            'second_reminder_overdue_at' => $invoice->second_reminder_overdue_at,
            'third_reminder_overdue_at' => $invoice->third_reminder_overdue_at,
            'debt_collector_overdue_at' => $invoice->debt_collector_overdue_at
        ]);

        // assert the model relations
        $this->assertNotNull($invoice->company);
        $this->assertNotNull($invoice->customer);

        // assert check scope unpaid
        $this->assertEquals(InvoiceStatus::Unpaid, $invoice->status);
    }

    /**
     * Test dispatch check overdue invoices job.
     *
     * @return void
     */
    public function test_dispatch_check_overdue_invoices_job()
    {
        Queue::fake();
        CheckOverdueInvoices::dispatch();
        Queue::assertPushed(CheckOverdueInvoices::class);
    }

    /**
     * Test dispatch change overdue invoice status job.
     *
     * @return void
     */
    public function test_dispatch_change_overdue_invoice_status_job()
    {
        $invoice = Invoice::factory()->create();

        // assert the instance
        $this->assertNotNull($invoice);
        $this->assertModelExists($invoice);

        Queue::fake();
        ChangeOverdueInvoiceStatus::dispatch($invoice);
        Queue::assertPushed(ChangeOverdueInvoiceStatus::class);
    }

    /**
     * Test dispatch check invoice reminder job.
     *
     * @return void
     */
    public function test_dispatch_check_invoice_reminder_job()
    {
        Queue::fake();
        CheckInvoiceReminder::dispatch();
        Queue::assertPushed(CheckInvoiceReminder::class);
    }

    /**
     * Test dispatch send invoice first reminder job.
     *
     * @return void
     */
    public function test_dispatch_send_invoice_first_reminder_job()
    {
        $invoice = Invoice::factory()->create();
        Queue::fake();
        SendInvoiceFirstReminder::dispatch($invoice);
        Queue::assertPushed(SendInvoiceFirstReminder::class);
    }

    /**
     * Test dispatch send invoice second reminder job
     *
     * @return void
     */
    public function test_dispatch_send_invoice_second_reminder_job()
    {
        $invoice = Invoice::factory()->create();
        Queue::fake();
        SendInvoiceSecondReminder::dispatch($invoice);
        Queue::assertPushed(SendInvoiceSecondReminder::class);
    }

    /**
     * Test dispatch send invoice third reminder job
     *
     * @return void
     */
    public function test_dispatch_send_invoice_third_reminder_job()
    {
        $invoice = Invoice::factory()->create();
        Queue::fake();
        SendInvoiceThirdReminder::dispatch($invoice);
        Queue::assertPushed(SendInvoiceThirdReminder::class);
    }
}
