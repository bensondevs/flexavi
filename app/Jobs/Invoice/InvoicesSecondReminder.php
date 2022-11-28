<?php

namespace App\Jobs\Invoice;

use App\Mail\Invoice\InvoiceFirstReminder;
use App\Models\Invoice\Invoice;
use App\Services\Invoice\InvoiceBackgroundService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class InvoicesSecondReminder implements ShouldQueue
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
    public function handle(): void
    {
        $service = new InvoiceBackgroundService();

        $invoices = Invoice::unpaid()->secondReminderSendable()->get();

        foreach ($invoices as $invoice) {
            $reminder = $invoice->reminder;

            $mailable = new InvoiceFirstReminder($invoice);
            if ($reminder->isUserSecondReminderSendable()) {
                $service->sendOwnersReminder($mailable, $invoice);
            }

            if ($reminder->isSecondReminderSendToCustomer() && $reminder->isCustomerSecondReminderSendable()) {
                $service->sendCustomerReminder($mailable, $invoice);
            }
        }
    }
}
