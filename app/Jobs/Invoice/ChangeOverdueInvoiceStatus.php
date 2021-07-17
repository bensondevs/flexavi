<?php

namespace App\Jobs\Invoice;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

use App\Models\Invoice;

use App\Enums\Invoice\InvoiceStatus;

class ChangeOverdueInvoiceStatus implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $timeout = 120;

    private $invoice;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(Invoice $invoice)
    {
        $this->invoice = $invoice;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $invoice = $this->invoice;

        if ($invoice->status == InvoiceStatus::Send) {
            $invoice->status = InvoiceStatus::PaymentOverdue;
        } else if ($invoice->status == InvoiceStatus::PaymentOverdue) {
            $invoice->status = InvoiceStatus::FirstReminder;
        } else if ($invoice->status == InvoiceStatus::FirstReminderSent) {
            $invoice->status = InvoiceStatus::SecondReminder;
        } else if ($invoice->status == InvoiceStatus::SecondReminderSent) {
            $invoice->status = InvoiceStatus::ThirdReminder;
        } else {
            $invoice->status = InvoiceStatus::OverdueDebtCollector;
        }

        $invoice->save();
    }
}
