<?php

namespace App\Jobs\Invoice;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\{ ShouldBeUnique, ShouldQueue };
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\{ InteractsWithQueue, SerializesModels };
use Illuminate\Support\Facades\Mail;

use App\Models\Invoice;
use App\Mail\Invoice\InvoiceFirstReminder;

class SendInvoiceFirstReminder implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Reminded invoice model container
     * 
     * @var \App\Models\Invoice|null
     */
    private $invoice;

    /**
     * Custom destination for sending email
     * 
     * @var array|null
     */
    private $reminderData;

    /**
     * Create a new job instance.
     *
     * @param  \App\Models\Invoice  $invoice
     * @param  array|null  $destination
     * @return void
     */
    public function __construct(Invoice $invoice, array $reminderData)
    {
        $this->invoice = $invoice;
        $this->reminderData = $reminderData;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $invoice = $this->invoice;
        $data = $this->reminderData;

        $destination = isset($data['destination_email']) ?
            $data['destination_email'] :
            $invoice->customer->email;
        $overdueAt = isset($data['overdue_at']) ?
            $data['overdue_at'] :
            next_week();

        $mailContent = new InvoiceFirstReminder($invoice);
        Mail::to($destination)->send($mailContent);

        $invoice->first_reminder_overdue_at = $overdueAt;
        $invoice->markFirstReminderSent();
    }
}