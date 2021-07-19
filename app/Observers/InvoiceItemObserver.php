<?php

namespace App\Observers;

use App\Models\InvoiceItem;

class InvoiceItemObserver
{
    /**
     * Handle the InvoiceItem "created" event.
     *
     * @param  \App\Models\InvoiceItem  $invoiceItem
     * @return void
     */
    public function created(InvoiceItem $invoiceItem)
    {
        $invoiceItem->recountInvoiceTotal();
    }

    /**
     * Handle the InvoiceItem "updated" event.
     *
     * @param  \App\Models\InvoiceItem  $invoiceItem
     * @return void
     */
    public function updated(InvoiceItem $invoiceItem)
    {
        $invoiceItem->recountInvoiceTotal();
    }

    /**
     * Handle the InvoiceItem "deleted" event.
     *
     * @param  \App\Models\InvoiceItem  $invoiceItem
     * @return void
     */
    public function deleted(InvoiceItem $invoiceItem)
    {
        $invoiceItem->recountInvoiceTotal();
    }

    /**
     * Handle the InvoiceItem "restored" event.
     *
     * @param  \App\Models\InvoiceItem  $invoiceItem
     * @return void
     */
    public function restored(InvoiceItem $invoiceItem)
    {
        $invoiceItem->recountInvoiceTotal();
    }

    /**
     * Handle the InvoiceItem "force deleted" event.
     *
     * @param  \App\Models\InvoiceItem  $invoiceItem
     * @return void
     */
    public function forceDeleted(InvoiceItem $invoiceItem)
    {
        $invoiceItem->recountInvoiceTotal();
    }
}
