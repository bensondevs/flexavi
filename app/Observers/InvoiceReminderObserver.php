<?php

namespace App\Observers;

use App\Models\Invoice\InvoiceReminder;
use App\Services\Invoice\InvoiceReminderService;

class InvoiceReminderObserver
{
    /**
     * Handle the InvoiceReminder "creating" event.
     *
     * @param InvoiceReminder $invoiceReminder
     * @return void
     */
    public function creating(InvoiceReminder $invoiceReminder): void
    {
        $invoiceReminder->id = generateUuid();
    }

    /**
     * Handle the InvoiceReminder "created" event.
     *
     * @param InvoiceReminder $invoiceReminder
     * @return void
     */
    public function created(InvoiceReminder $invoiceReminder): void
    {
        //
    }

    /**
     * Handle the InvoiceReminder "updated" event.
     *
     * @param InvoiceReminder $invoiceReminder
     * @return void
     */
    public function updated(InvoiceReminder $invoiceReminder): void
    {
        InvoiceReminderService::customerReminderSent($invoiceReminder);
    }

    /**
     * Handle the InvoiceReminder "deleted" event.
     *
     * @param InvoiceReminder $invoiceReminder
     * @return void
     */
    public function deleted(InvoiceReminder $invoiceReminder): void
    {
        //
    }

    /**
     * Handle the InvoiceReminder "restored" event.
     *
     * @param InvoiceReminder $invoiceReminder
     * @return void
     */
    public function restored(InvoiceReminder $invoiceReminder): void
    {
        //
    }

    /**
     * Handle the InvoiceReminder "force deleted" event.
     *
     * @param InvoiceReminder $invoiceReminder
     * @return void
     */
    public function forceDeleted(InvoiceReminder $invoiceReminder): void
    {
        //
    }
}
