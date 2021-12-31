<?php

namespace App\Jobs\Invoice;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\{ ShouldBeUnique, ShouldQueue };
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\{ InteractsWithQueue, SerializesModels };

use App\Models\Invoice;
use App\Enums\Invoice\InvoiceStatus as Status;

class SyncInvoiceStatuses implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        // Query all overdue invoices
        // And change the status into PaymentOverdue
        // Afther that inform to the company owners
        $odInvoices = Invoice::overdue()->get();
        foreach ($odInvoices as $odInvoice) {
            $odInvoice->status = Status::PaymentOverdue;
            $odInvoice->save();

            // Notify?
        }

        // Query all first reminder overdue invoices
        // And change the status into FirstReminderOverdue
        // After that inform to the company owners
        $froInvoices = Invoice::firstReminderOverdue()->get();
        foreach ($froInvoices as $froInvoice) {
            $froInvoice->status = Status::FirstReminderOverdue;
            $froInvoice->save();

            // Notify?
        }

        // Query all second reminder overdue invoices
        // And change the status to SecondReminderOverdue
        // After that inform to the company owners
        $sroInvoices = Invoice::secondReminderOverdue()->get();
        foreach ($sroInvoices as $sroInvoice) {
            $sroInvoice->status = Status::SecondReminderOverdue;
            $sroInvoice->save();

            // Notify?
        }

        // Query all third reminder overdue invoices
        // And change the status to ThirdReminderOverdue
        // After that inform to the company owners
        $troInvoices = Invoice::thirdReminderOverdue()->get();
        foreach ($troInvoices as $troInvoice) {
            $troInvoice->status = Status::ThirdReminderOverdue;
            $troInvoice->save();

            // Notify?
        }
    }
}
