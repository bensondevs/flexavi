<?php

namespace App\Observers;

use App\Models\Invoice\InvoiceItem;

class InvoiceItemObserver
{
    /**
     * Handle the InvoiceItem "creating" event.
     *
     * @param InvoiceItem $invoiceItem
     * @return void
     */
    public function creating(InvoiceItem $invoiceItem): void
    {
        $invoiceItem->id = generateUuid();
    }

    /**
     * Handle the InvoiceItem "created" event.
     *
     * @param InvoiceItem $invoiceItem
     * @return void
     */
    public function created(InvoiceItem $invoiceItem): void
    {
        //
    }

    /**
     * Handle the InvoiceItem "updated" event.
     *
     * @param InvoiceItem $invoiceItem
     * @return void
     */
    public function updated(InvoiceItem $invoiceItem): void
    {
        //
    }

    /**
     * Handle the InvoiceItem "deleted" event.
     *
     * @param InvoiceItem $invoiceItem
     * @return void
     */
    public function deleted(InvoiceItem $invoiceItem): void
    {
        //
    }

    /**
     * Handle the InvoiceItem "restored" event.
     *
     * @param InvoiceItem $invoiceItem
     * @return void
     */
    public function restored(InvoiceItem $invoiceItem): void
    {
        //
    }

    /**
     * Handle the InvoiceItem "force deleted" event.
     *
     * @param InvoiceItem $invoiceItem
     * @return void
     */
    public function forceDeleted(InvoiceItem $invoiceItem): void
    {
        //
    }
}
