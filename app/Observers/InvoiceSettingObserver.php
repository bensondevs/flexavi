<?php

namespace App\Observers;

use App\Models\Setting\InvoiceSetting;

class InvoiceSettingObserver
{
    /**
     * Handle the InvoiceSetting "created" event.
     *
     * @param InvoiceSetting $invoiceSetting
     * @return void
     */
    public function created(InvoiceSetting $invoiceSetting): void
    {
        //
    }

    /**
     * Handle the InvoiceSetting "creating" event.
     *
     * @param InvoiceSetting $invoiceSetting
     * @return void
     */
    public function creating(InvoiceSetting $invoiceSetting): void
    {
        $invoiceSetting->id = generateUuid();
    }

    /**
     * Handle the InvoiceSetting "updated" event.
     *
     * @param InvoiceSetting $invoiceSetting
     * @return void
     */
    public function updated(InvoiceSetting $invoiceSetting): void
    {
        //
    }

    /**
     * Handle the InvoiceSetting "deleted" event.
     *
     * @param InvoiceSetting $invoiceSetting
     * @return void
     */
    public function deleted(InvoiceSetting $invoiceSetting): void
    {
        //
    }

    /**
     * Handle the InvoiceSetting "restored" event.
     *
     * @param InvoiceSetting $invoiceSetting
     * @return void
     */
    public function restored(InvoiceSetting $invoiceSetting): void
    {
        //
    }

    /**
     * Handle the InvoiceSetting "force deleted" event.
     *
     * @param InvoiceSetting $invoiceSetting
     * @return void
     */
    public function forceDeleted(InvoiceSetting $invoiceSetting): void
    {
        //
    }
}
