<?php

namespace App\Observers;

use App\Enums\Invoice\InvoiceStatus;
use App\Models\Invoice\Invoice;
use App\Services\Invoice\{InvoiceLoggingService, InvoiceReminderService, InvoiceService, InvoiceSettingService};

class InvoiceObserver
{
    /**
     * Invoice logging service
     */
    private InvoiceLoggingService $invoiceLoggingService;

    /**
     * Observer constructor.
     *
     * @param InvoiceLoggingService $invoiceLoggingService
     */
    public function __construct(InvoiceLoggingService $invoiceLoggingService)
    {
        $this->invoiceLoggingService = $invoiceLoggingService;
    }


    /**
     * Handle the Invoice "creating" event.
     *
     * @param Invoice $invoice
     * @return void
     */
    public function creating(Invoice $invoice): void
    {
        $invoice->id = generateUuid();
    }

    /**
     * Handle the Invoice "created" event.
     *
     * @param Invoice $invoice
     * @return void
     */
    public function created(Invoice $invoice): void
    {
        if ($invoice->status === InvoiceStatus::Sent) {
            $invoice->generateNumber();
            $invoice->saveQuietly();
        }

        app(InvoiceReminderService::class)->generateDefaultReminder($invoice);
        app(InvoiceSettingService::class)->generateDefaultSetting($invoice);
    }

    /**
     * Handle the Invoice "updated" event.
     *
     * @param Invoice $invoice
     * @return void
     */
    public function updated(Invoice $invoice): void
    {
        InvoiceService::statusChanged($invoice);
        $this->invoiceLoggingService->updated($invoice);
    }

    /**
     * Handle the Invoice "restored" event.
     *
     * @param Invoice $invoice
     * @return void
     */
    public function restored(Invoice $invoice): void
    {
        $this->invoiceLoggingService->deleted($invoice);
    }

    /**
     * Handle the Invoice "deleted" event.
     *
     * @param Invoice $invoice
     * @return void
     */
    public function deleted(Invoice $invoice): void
    {
        if (!$invoice->isForceDeleting()) {
            $this->invoiceLoggingService->deleted($invoice);
        }
    }

    /**
     * Handle the Invoice "force deleted" event.
     *
     * @param Invoice $invoice
     * @return void
     */
    public function forceDeleted(Invoice $invoice): void
    {
        //
    }
}
