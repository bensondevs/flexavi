<?php

namespace App\Observers;

use App\Models\Subscription\Subscription;

class SubscriptionObserver
{
    /**
     * Handle the Subscription "creating" event.
     *
     * @param Subscription $subscription
     * @return void
     */
    public function creating(Subscription $subscription): void
    {
        $subscription->id = generateUuid();
    }

    /**
     * Handle the Subscription "created" event.
     *
     * @param Subscription $subscription
     * @return void
     */
    public function created(Subscription $subscription): void
    {
        //
    }

    /**
     * Handle the Subscription "updated" event.
     *
     * @param Subscription $subscription
     * @return void
     */
    public function updated(Subscription $subscription): void
    {
        //
    }


    /**
     * Handle the Subscription "deleted" event.
     *
     * @param Subscription $subscription
     * @return void
     */
    public function deleted(Subscription $subscription): void
    {
        //
    }

    /**
     * Handle the Subscription "restored" event.
     *
     * @param Subscription $subscription
     * @return void
     */
    public function restored(Subscription $subscription): void
    {
        //
    }

    /**
     * Handle the Subscription "force deleted" event.
     *
     * @param Subscription $subscription
     * @return void
     */
    public function forceDeleted(Subscription $subscription): void
    {
        //
    }
}
