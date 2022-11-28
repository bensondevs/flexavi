<?php

namespace App\Mail\Invoice;

use App\Models\Invoice\Invoice;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class InvoiceFirstReminder extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Invoice model container
     *
     * @var Invoice
     */
    private Invoice $invoice;

    /**
     * Create a new message instance.
     *
     * @param Invoice $invoice
     * @return void
     */
    public function __construct(Invoice $invoice)
    {
        $this->invoice = $invoice;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build(): static
    {
        $invoice = $this->invoice;
        return $this->view('mails.invoices.first_reminder')
            ->with(['invoice' => $invoice]);
    }
}
