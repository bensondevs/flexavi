<?php

namespace App\Observers;

use App\Models\PaymentPickup\PaymentPickupItem;

class PaymentPickupItemObserver
{
    /**
     * Handle the PaymentPickupItem "creating" event.
     *
     * @param PaymentPickupItem $paymentPickupItem
     * @return void
     */
    public function creating(PaymentPickupItem $paymentPickupItem)
    {
        $paymentPickupItem->id = generateUuid();
    }

    /**
     * Handle the PaymentPickupItem "created" event.
     *
     * @param PaymentPickupItem $paymentPickupItem
     * @return void
     */
    public function created(PaymentPickupItem $paymentPickupItem)
    {
        //
    }

    /**
     * Handle the PaymentPickupItem "updated" event.
     *
     * @param PaymentPickupItem $paymentPickupItem
     * @return void
     */
    public function updated(PaymentPickupItem $paymentPickupItem)
    {
        //
    }

    /**
     * Handle the PaymentPickupItem "deleted" event.
     *
     * @param PaymentPickupItem $paymentPickupItem
     * @return void
     */
    public function deleted(PaymentPickupItem $paymentPickupItem)
    {
        //
    }

    /**
     * Handle the PaymentPickupItem "restored" event.
     *
     * @param PaymentPickupItem $paymentPickupItem
     * @return void
     */
    public function restored(PaymentPickupItem $paymentPickupItem)
    {
        //
    }

    /**
     * Handle the PaymentPickupItem "force deleted" event.
     *
     * @param PaymentPickupItem $paymentPickupItem
     * @return void
     */
    public function forceDeleted(PaymentPickupItem $paymentPickupItem)
    {
        //
    }
}
