<?php

namespace App\Observers;

use App\Models\PaymentPickup\PaymentTerm;

class PaymentTermObserver
{
    /**
     * Handle the PaymentTerm "created" event.
     *
     * @param PaymentTerm $paymentTerm
     * @return void
     */
    public function created(PaymentTerm $paymentTerm)
    {
        $paymentTerm->recountInvoiceTermsTotal();
    }

    /**
     * Handle the PaymentTerm "updated" event.
     *
     * @param PaymentTerm $paymentTerm
     * @return void
     */
    public function updated(PaymentTerm $paymentTerm)
    {
        $paymentTerm->recountInvoiceTermsTotal();
    }

    /**
     * Handle the PaymentTerm "deleted" event.
     *
     * @param PaymentTerm $paymentTerm
     * @return void
     */
    public function deleted(PaymentTerm $paymentTerm)
    {
        $paymentTerm->recountInvoiceTermsTotal();
    }

    /**
     * Handle the PaymentTerm "restored" event.
     *
     * @param PaymentTerm $paymentTerm
     * @return void
     */
    public function restored(PaymentTerm $paymentTerm)
    {
        $paymentTerm->recountInvoiceTermsTotal();
    }

    /**
     * Handle the PaymentTerm "force deleted" event.
     *
     * @param PaymentTerm $paymentTerm
     * @return void
     */
    public function forceDeleted(PaymentTerm $paymentTerm)
    {
        $paymentTerm->recountInvoiceTermsTotal();
    }
}
