<?php

namespace App\Observers;

use App\Models\Invoice;
use App\Enums\Invoice\InvoiceStatus as Status;

class InvoiceObserver
{
    /**
     * Handle the Invoice "creating" event.
     *
     * @param  \App\Models\Invoice  $invoice
     * @return void
     */
    public function creating(Invoice $invoice)
    {
        $invoice->id = generateUuid();

        if (! $invoice->invoice_number) {
            $invoice->invoice_number = $invoice->generateNumber();
        }
    }

    /**
     * Handle the Invoice "created" event.
     *
     * @param  \App\Models\Invoice  $invoice
     * @return void
     */
    public function created(Invoice $invoice)
    {
        //
    }

    /**
     * Handle the Invoice "updated" event.
     *
     * @param  \App\Models\Invoice  $invoice
     * @return void
     */
    public function updated(Invoice $invoice)
    {
        if ($invoice->isDirty('status')) {
            $sentStatusCode = Status::Sent;

            $currentStatus = $invoice->status;
            $previousStatus = $invoice->getOriginal('status');
            if ($currentStatus == $sentStatusCode && $previousStatus < $sentStatusCode) {
                $invoice->generateNumber();
                $invoice->save();
            }
        }
    }

    /**
     * Handle the Invoice "deleted" event.
     *
     * @param  \App\Models\Invoice  $invoice
     * @return void
     */
    public function deleted(Invoice $invoice)
    {
        //
    }

    /**
     * Handle the Invoice "restored" event.
     *
     * @param  \App\Models\Invoice  $invoice
     * @return void
     */
    public function restored(Invoice $invoice)
    {
        //
    }

    /**
     * Handle the Invoice "force deleted" event.
     *
     * @param  \App\Models\Invoice  $invoice
     * @return void
     */
    public function forceDeleted(Invoice $invoice)
    {
        //
    }
}
