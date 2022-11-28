<?php

namespace Tests\Unit\Models\InvoiceReminder;

use App\Models\Invoice\Invoice;
use App\Models\Invoice\InvoiceLog;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Tests\TestCase;

/**
 * @see \App\Models\Invoice\InvoiceReminder
 *      To see model class
 */
class InvoiceReminderTest extends TestCase
{
    /**
     * To see if model exists
     * @test
     * @return void
     */
    public function model_exists(): void
    {
        $this->assertTrue(class_exists(\App\Models\Invoice\InvoiceReminder::class));
    }

    /**
     * Testing it invoice log has invoice relationship
     *
     * @test
     * @return void
     * @see \App\Models\Invoice\InvoiceLog::invoice()
     *      To see model relationship
     */
    public function it_invoice_log_has_invoice_relationship(): void
    {
        $invoice = Invoice::factory()->create();
        $log = InvoiceLog::factory()->for($invoice)->create();
        $this->assertInstanceOf(Invoice::class, $log->invoice);
        $this->assertInstanceOf(BelongsTo::class, $log->invoice());
        $this->assertEquals($log->invoice->load('company'), $invoice->fresh()->load('company'));
    }
}
