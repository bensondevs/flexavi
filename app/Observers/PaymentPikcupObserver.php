<?php

namespace App\Observers;

use App\Models\PaymentPickup;

class PaymentPikcupObserver
{
    /**
     * Handle the PaymentPickup "creating" event.
     *
     * @param  \App\Models\PaymentPickup  $paymentPickup
     * @return void
     */
    public function creating(PaymentPickup $paymentPickup)
    {
        $paymentPickup = generateUuid();
    }

    /**
     * Handle the PaymentPickup "created" event.
     *
     * @param  \App\Models\PaymentPickup  $paymentPickup
     * @return void
     */
    public function created(PaymentPickup $paymentPickup)
    {
        if ($paymentPickup->picked_up_amount && $paymentPickup->picked_up_at) {
            $paymentPickup->picked_up_at = now();
        }
    }

    /**
     * Handle the PaymentPickup "updated" event.
     *
     * @param  \App\Models\PaymentPickup  $paymentPickup
     * @return void
     */
    public function updated(PaymentPickup $paymentPickup)
    {
        if ($paymentPickup->picked_up_amount && $paymentPickup->picked_up_at) {
            $paymentPickup->picked_up_at = now();
        }
    }

    /**
     * Handle the PaymentPickup "deleted" event.
     *
     * @param  \App\Models\PaymentPickup  $paymentPickup
     * @return void
     */
    public function deleted(PaymentPickup $paymentPickup)
    {
        //
    }

    /**
     * Handle the PaymentPickup "restored" event.
     *
     * @param  \App\Models\PaymentPickup  $paymentPickup
     * @return void
     */
    public function restored(PaymentPickup $paymentPickup)
    {
        //
    }

    /**
     * Handle the PaymentPickup "force deleted" event.
     *
     * @param  \App\Models\PaymentPickup  $paymentPickup
     * @return void
     */
    public function forceDeleted(PaymentPickup $paymentPickup)
    {
        //
    }
}
