<?php

namespace App\Observers;

use App\Models\Subscription\SubscriptionPlanPeriod;

class SubscriptionPlanPeriodObserver
{
    /**
     * Handle the SubscriptionPlanPeriod "creating" event.
     *
     * @param SubscriptionPlanPeriod $subscriptionPlanPeriod
     * @return void
     */
    public function creating(SubscriptionPlanPeriod $subscriptionPlanPeriod): void
    {
        $subscriptionPlanPeriod->id = generateUuid();
    }

    /**
     * Handle the SubscriptionPlanPeriod "created" event.
     *
     * @param SubscriptionPlanPeriod $subscriptionPlanPeriod
     * @return void
     */
    public function created(SubscriptionPlanPeriod $subscriptionPlanPeriod): void
    {
        //
    }

    /**
     * Handle the SubscriptionPlanPeriod "updated" event.
     *
     * @param SubscriptionPlanPeriod $subscriptionPlanPeriod
     * @return void
     */
    public function updated(SubscriptionPlanPeriod $subscriptionPlanPeriod): void
    {
        //
    }

    /**
     * Handle the SubscriptionPlanPeriod "deleted" event.
     *
     * @param SubscriptionPlanPeriod $subscriptionPlanPeriod
     * @return void
     */
    public function deleted(SubscriptionPlanPeriod $subscriptionPlanPeriod): void
    {
        //
    }

    /**
     * Handle the SubscriptionPlanPeriod "restored" event.
     *
     * @param SubscriptionPlanPeriod $subscriptionPlanPeriod
     * @return void
     */
    public function restored(SubscriptionPlanPeriod $subscriptionPlanPeriod): void
    {
        //
    }

    /**
     * Handle the SubscriptionPlanPeriod "force deleted" event.
     *
     * @param SubscriptionPlanPeriod $subscriptionPlanPeriod
     * @return void
     */
    public function forceDeleted(SubscriptionPlanPeriod $subscriptionPlanPeriod): void
    {
        //
    }
}
