<?php

namespace App\Observers;

use App\Models\CompanySubscriptionPurchase;

class CompanySubscriptionPurchaseObserver
{
    /**
     * Handle the CompanySubscriptionPurchase "creating" event.
     *
     * @param CompanySubscriptionPurchase $companySubscriptionPurchase
     * @return void
     */
    public function creating(CompanySubscriptionPurchase $companySubscriptionPurchase)
    {
        $companySubscriptionPurchase->id = generateUuid();
    }

    /**
     * Handle the CompanySubscriptionPurchase "created" event.
     *
     * @param CompanySubscriptionPurchase $companySubscriptionPurchase
     * @return void
     */
    public function created(CompanySubscriptionPurchase $companySubscriptionPurchase)
    {
        //
    }

    /**
     * Handle the CompanySubscriptionPurchase "updated" event.
     *
     * @param CompanySubscriptionPurchase $companySubscriptionPurchase
     * @return void
     */
    public function updated(CompanySubscriptionPurchase $companySubscriptionPurchase)
    {
        //
    }

    /**
     * Handle the CompanySubscriptionPurchase "deleted" event.
     *
     * @param CompanySubscriptionPurchase $companySubscriptionPurchase
     * @return void
     */
    public function deleted(CompanySubscriptionPurchase $companySubscriptionPurchase)
    {
        //
    }

    /**
     * Handle the CompanySubscriptionPurchase "restored" event.
     *
     * @param CompanySubscriptionPurchase $companySubscriptionPurchase
     * @return void
     */
    public function restored(CompanySubscriptionPurchase $companySubscriptionPurchase)
    {
        //
    }

    /**
     * Handle the CompanySubscriptionPurchase "force deleted" event.
     *
     * @param CompanySubscriptionPurchase $companySubscriptionPurchase
     * @return void
     */
    public function forceDeleted(CompanySubscriptionPurchase $companySubscriptionPurchase)
    {
        //
    }
}
