<?php

namespace App\Services\Invoice;

use App\Models\Invoice\Invoice;
use App\Models\Invoice\InvoiceReminder;
use App\Models\Setting\InvoiceSetting;
use App\Repositories\Invoice\InvoiceReminderRepository;

class InvoiceReminderService
{
    /**
     * Invoice reminder repository class container
     *
     * @var InvoiceReminderRepository
     */
    private InvoiceReminderRepository $invoiceReminderRepository;

    /**
     * Create New Service Instance
     *
     * @return void
     */
    public function __construct(
        InvoiceReminderRepository $invoiceReminderRepository
    ) {
        $this->invoiceReminderRepository = $invoiceReminderRepository;
    }

    /**
     * Handle invoice reminder update
     *
     * @param InvoiceReminder $invoiceReminder
     * @return void
     */
    public static function customerReminderSent(InvoiceReminder $invoiceReminder): void
    {
        if (!$invoiceReminder->wasChanged()) {
            return;
        }

        $invoiceSetting = InvoiceSetting::where('invoice_id', $invoiceReminder->invoice_id)->first();


        if ($invoiceReminder->wasChanged('customer_first_reminder_sent_at') && is_null($invoiceReminder->getOriginal('second_reminder_at'))) {
            $invoiceReminder->second_reminder_at = $invoiceReminder->first_reminder_at->copy()->addDays($invoiceSetting->second_reminder_days);
        }

        if ($invoiceReminder->wasChanged('customer_second_reminder_sent_at') && is_null($invoiceReminder->getOriginal('third_reminder_at'))) {
            $invoiceReminder->third_reminder_at = $invoiceReminder->second_reminder_at->copy()->addDays($invoiceSetting->third_reminder_days);
        }

        if ($invoiceReminder->wasChanged('customer_third_reminder_sent_at') && is_null($invoiceReminder->getOriginal('sent_to_debt_collector_at'))) {
            $invoiceReminder->sent_to_debt_collector_at = $invoiceReminder->third_reminder_at->copy()->addDays($invoiceSetting->debt_collector_reminder_days);
        }
        $invoiceReminder->saveQuietly();
    }

    /**
     * Generate default reminder
     *
     * @param Invoice $invoice
     * @return void
     */
    public function generateDefaultReminder(Invoice $invoice): void
    {
        $firstReminderAt = $invoice->due_date;

        $data = [
            'invoice_id' => $invoice->id,
            'overdue_reminder_at' => $invoice->due_date,
            'first_reminder_at' => $firstReminderAt,
        ];

        $this->invoiceReminderRepository->save($data);
    }
}
