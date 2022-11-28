<?php

namespace App\Services\Invoice;

use App\Jobs\Invoice\Reminder\CustomerReminder;
use App\Jobs\Invoice\Reminder\OwnerReminder;
use App\Jobs\SendMail;
use App\Mail\Invoice\SendInvoice;
use App\Models\Invoice\Invoice;
use Illuminate\Mail\Mailable;

class InvoiceBackgroundService
{
    /**
     * Send invoice to customer email
     *
     * @param Invoice $invoice
     * @return void
     */
    public static function send(Invoice $invoice): void
    {
        $invoice = $invoice->load('customer', 'items.workService');
        $mailable = new SendInvoice($invoice);
        $job = new SendMail($mailable, $invoice->customer->email);
        dispatch($job);
    }


    /**
     * Send reminder email to company owners
     *
     * @param Mailable $mailable
     * @param Invoice $invoice
     * @return void
     */
    public static function sendOwnersReminder(Mailable $mailable, Invoice $invoice): void
    {
        $company = $invoice->company;
        $owners = $company->owners;
        foreach ($owners as $owner) {
            if ($user = $owner->user) {
                dispatch(new OwnerReminder($mailable, $invoice, $user->email))->delay(4);
            }
        }
    }

    /**
     * Send reminder email to company owners
     *
     * @param Mailable $mailable
     * @param Invoice $invoice
     * @return void
     */
    public static function sendCustomerReminder(Mailable $mailable, Invoice $invoice): void
    {
        dispatch(new CustomerReminder($mailable, $invoice, $invoice->customer->email));
    }
}
