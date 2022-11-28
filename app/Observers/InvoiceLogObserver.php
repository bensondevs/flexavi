<?php

namespace App\Observers;

use App\Models\Invoice\InvoiceLog;

class InvoiceLogObserver
{
    /**
     * Handle the InvoiceLog "creating" event.
     *
     * @param InvoiceLog $invoiceLog
     * @return void
     */
    public function creating(InvoiceLog $invoiceLog): void
    {
        $invoiceLog->id = generateUuid();
    }

    /**
     * Handle the InvoiceLog "created" event.
     *
     * @param InvoiceLog $invoiceLog
     * @return void
     */
    public function created(InvoiceLog $invoiceLog): void
    {
        //
    }

    /**
     * Handle the InvoiceLog "updated" event.
     *
     * @param InvoiceLog $invoiceLog
     * @return void
     */
    public function updated(InvoiceLog $invoiceLog): void
    {
        //
    }

    /**
     * Handle the InvoiceLog "deleted" event.
     *
     * @param InvoiceLog $invoiceLog
     * @return void
     */
    public function deleted(InvoiceLog $invoiceLog): void
    {
        //
    }

    /**
     * Handle the InvoiceLog "restored" event.
     *
     * @param InvoiceLog $invoiceLog
     * @return void
     */
    public function restored(InvoiceLog $invoiceLog): void
    {
        //
    }

    /**
     * Handle the InvoiceLog "force deleted" event.
     *
     * @param InvoiceLog $invoiceLog
     * @return void
     */
    public function forceDeleted(InvoiceLog $invoiceLog): void
    {
        //
    }
}
