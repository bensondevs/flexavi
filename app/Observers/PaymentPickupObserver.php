<?php

namespace App\Observers;

use App\Models\PaymentPickup\PaymentPickup;

class PaymentPickupObserver
{
    /**
     * Handle the PaymentPickup "creating" event.
     *
     * @param PaymentPickup $paymentPickup
     * @return void
     */
    public function creating(PaymentPickup $paymentPickup)
    {
        $paymentPickup->id = generateUuid();
    }

    /**
     * Handle the PaymentPickup "created" event.
     *
     * @param PaymentPickup $paymentPickup
     * @return void
     */
    public function created(PaymentPickup $paymentPickup)
    {
        //
    }

    /**
     * Handle the PaymentPickup "updated" event.
     *
     * @param PaymentPickup $paymentPickup
     * @return void
     */
    public function updated(PaymentPickup $paymentPickup)
    {
        if ($paymentPickup->isDirty('picked_up_amount')) {
            if (!$paymentPickup->getOriginal('picked_up_amount')) {
                $paymentPickup->picked_up_at = now();
            }
        }
    }

    /**
     * Handle the PaymentPickup "deleted" event.
     *
     * @param PaymentPickup $paymentPickup
     * @return void
     */
    public function deleted(PaymentPickup $paymentPickup)
    {
        //
    }

    /**
     * Handle the PaymentPickup "restored" event.
     *
     * @param PaymentPickup $paymentPickup
     * @return void
     */
    public function restored(PaymentPickup $paymentPickup)
    {
        //
    }

    /**
     * Handle the PaymentPickup "force deleted" event.
     *
     * @param PaymentPickup $paymentPickup
     * @return void
     */
    public function forceDeleted(PaymentPickup $paymentPickup)
    {
        //
    }
}
