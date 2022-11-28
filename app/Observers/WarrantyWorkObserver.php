<?php

namespace App\Observers;

use App\Models\Warranty\WarrantyWork;

class WarrantyWorkObserver
{
    /**
     * Handle the WarrantyWork "creating" event.
     *
     * @param WarrantyWork $warrantyWork
     * @return void
     */
    public function creating(WarrantyWork $warrantyWork)
    {
        $warrantyWork->id = generateUuid();
    }

    /**
     * Handle the WarrantyWork "created" event.
     *
     * @param WarrantyWork $warrantyWork
     * @return void
     */
    public function created(WarrantyWork $warrantyWork)
    {
        $warranty = $warrantyWork->warranty;
        $warranty->addAmount($warrantyWork->amount);
    }

    /**
     * Handle the WarrantyWork "updated" event.
     *
     * @param WarrantyWork $warrantyWork
     * @return void
     */
    public function updated(WarrantyWork $warrantyWork)
    {
        if ($warrantyWork->isDirty('amount')) {
            $warranty = $warrantyWork->warranty;

            //
            // Get warranty difference before and after update
            //
            $previousAmount = $warrantyWork->getOriginal('amount');
            $currentAmount = $warrantyWork->amount;
            $difference = $currentAmount - $previousAmount;

            $warrantyWork->addAmount($difference);
        }
    }

    /**
     * Handle the WarrantyWork "deleted" event.
     *
     * @param WarrantyWork $warrantyWork
     * @return void
     */
    public function deleted(WarrantyWork $warrantyWork)
    {
        $warranty = $warrantyWork->warranty;
        $warranty->subsAmount($warrantyWork->amount);
    }

    /**
     * Handle the WarrantyWork "restored" event.
     *
     * @param WarrantyWork $warrantyWork
     * @return void
     */
    public function restored(WarrantyWork $warrantyWork)
    {
        $warranty = $warrantyWork->warranty;
        $warranty->addAmount($warrantyWork->amount);
    }

    /**
     * Handle the WarrantyWork "force deleted" event.
     *
     * @param WarrantyWork $warrantyWork
     * @return void
     */
    public function forceDeleted(WarrantyWork $warrantyWork)
    {
        $warranty = $warrantyWork->warranty;
        $warranty->subsAmount($warrantyWork->amount);
    }
}
