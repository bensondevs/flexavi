<?php

namespace App\Observers;

use App\Models\WarrantyWork;

class WarrantyWorkObserver
{
    /**
     * Handle the WarrantyWork "creating" event.
     *
     * @param  \App\Models\WarrantyWork  $warrantyWork
     * @return void
     */
    public function creating(WarrantyWork $warrantyWork)
    {
        $warrantyWork->id = generateUuid();
    }

    /**
     * Handle the WarrantyWork "created" event.
     *
     * @param  \App\Models\WarrantyWork  $warrantyWork
     * @return void
     */
    public function created(WarrantyWork $warrantyWork)
    {
        //
    }

    /**
     * Handle the WarrantyWork "updated" event.
     *
     * @param  \App\Models\WarrantyWork  $warrantyWork
     * @return void
     */
    public function updated(WarrantyWork $warrantyWork)
    {
        //
    }

    /**
     * Handle the WarrantyWork "deleted" event.
     *
     * @param  \App\Models\WarrantyWork  $warrantyWork
     * @return void
     */
    public function deleted(WarrantyWork $warrantyWork)
    {
        //
    }

    /**
     * Handle the WarrantyWork "restored" event.
     *
     * @param  \App\Models\WarrantyWork  $warrantyWork
     * @return void
     */
    public function restored(WarrantyWork $warrantyWork)
    {
        //
    }

    /**
     * Handle the WarrantyWork "force deleted" event.
     *
     * @param  \App\Models\WarrantyWork  $warrantyWork
     * @return void
     */
    public function forceDeleted(WarrantyWork $warrantyWork)
    {
        //
    }
}
