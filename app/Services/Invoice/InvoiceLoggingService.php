<?php

namespace App\Services\Invoice;

use App\Enums\Invoice\InvoiceStatus;
use App\Models\Invoice\Invoice;

class InvoiceLoggingService extends InvoiceLogService
{

    /**
     * Handle the invoice updated event.
     *
     * @param Invoice $invoice
     * @return void
     */
    public function updated(Invoice $invoice): void
    {
        $this->write($invoice, 'updated');
        $this->write($invoice, 'status_changed', [
            'old_status' => InvoiceStatus::getDescription($invoice->getOriginal('status')),
            'new_status' => $invoice->status_description,
        ]);
    }

    /**
     * Handle the invoice restored event.
     *
     * @param Invoice $invoice
     * @return void
     */
    public function restored(Invoice $invoice): void
    {
        $this->write($invoice, 'restored');
    }

    /**
     * Handle the invoice deleted event.
     *
     * @param Invoice $invoice
     * @return void
     */
    public function deleted(Invoice $invoice): void
    {
        $this->write($invoice, 'deleted');
    }

    /**
     * Handle the invoice resend invoice.
     *
     * @param Invoice $invoice
     * @return void
     */
    public function resend(Invoice $invoice): void
    {
        $this->write($invoice, 'resend');
    }
}
