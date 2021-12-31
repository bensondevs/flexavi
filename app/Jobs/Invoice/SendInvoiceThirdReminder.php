<?php

namespace App\Jobs\Invoice;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\{ ShouldBeUnique, ShouldQueue };
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\{ InteractsWithQueue, SerializesModels };
use Illuminate\Support\Facades\Mail;

use App\Models\Invoice;
use App\Mail\Invoice\InvoiceThirdReminder;

class SendInvoiceThirdReminder implements ShouldQueue
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
     * @var string|null
     */
    private $destination;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(Invoice $invoice, string $destination = null)
    {
        $this->invoice = $invoice;
        $this->destination = $destination;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $invoice = $this->invoice;
        $destination = $this->destination ?: $invoice->customer->email;

        $mailContent = new InvoiceThirdReminder($invoice);
        Mail::to($destination)->send($mailContent);

        $invoice->markThirdReminderSent();
    }
}