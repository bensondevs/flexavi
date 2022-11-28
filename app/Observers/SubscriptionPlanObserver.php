<?php

namespace App\Observers;

use App\Models\Subscription\SubscriptionPlan;

class SubscriptionPlanObserver
{
    /**
     * Handle the SubscriptionPlan "creating" event.
     *
     * @param SubscriptionPlan $subscriptionPlan
     * @return void
     */
    public function creating(SubscriptionPlan $subscriptionPlan): void
    {
        $subscriptionPlan->id = generateUuid();
    }

    /**
     * Handle the SubscriptionPlan "created" event.
     *
     * @param SubscriptionPlan $subscriptionPlan
     * @return void
     */
    public function created(SubscriptionPlan $subscriptionPlan): void
    {
        //
    }

    /**
     * Handle the SubscriptionPlan "updated" event.
     *
     * @param SubscriptionPlan $subscriptionPlan
     * @return void
     */
    public function updated(SubscriptionPlan $subscriptionPlan): void
    {
        //
    }

    /**
     * Handle the SubscriptionPlan "deleted" event.
     *
     * @param SubscriptionPlan $subscriptionPlan
     * @return void
     */
    public function deleted(SubscriptionPlan $subscriptionPlan): void
    {
        //
    }

    /**
     * Handle the SubscriptionPlan "restored" event.
     *
     * @param SubscriptionPlan $subscriptionPlan
     * @return void
     */
    public function restored(SubscriptionPlan $subscriptionPlan): void
    {
        //
    }

    /**
     * Handle the SubscriptionPlan "force deleted" event.
     *
     * @param SubscriptionPlan $subscriptionPlan
     * @return void
     */
    public function forceDeleted(SubscriptionPlan $subscriptionPlan): void
    {
        //
    }
}
