<?php

namespace App\Observers;

use App\Models\Warranty;

class WarrantyObserver
{
    /**
     * Handle the Warranty "creating" event.
     *
     * @param  \App\Models\Warranty  $warranty
     * @return void
     */
    public function creating(Warranty $warranty)
    {
        $warranty->id = generateUuid();
    }

    /**
     * Handle the Warranty "created" event.
     *
     * @param  \App\Models\Warranty  $warranty
     * @return void
     */
    public function created(Warranty $warranty)
    {
        //
    }

    /**
     * Handle the Warranty "updated" event.
     *
     * @param  \App\Models\Warranty  $warranty
     * @return void
     */
    public function updated(Warranty $warranty)
    {
        //
    }

    /**
     * Handle the Warranty "deleted" event.
     *
     * @param  \App\Models\Warranty  $warranty
     * @return void
     */
    public function deleted(Warranty $warranty)
    {
        //
    }

    /**
     * Handle the Warranty "restored" event.
     *
     * @param  \App\Models\Warranty  $warranty
     * @return void
     */
    public function restored(Warranty $warranty)
    {
        //
    }

    /**
     * Handle the Warranty "force deleted" event.
     *
     * @param  \App\Models\Warranty  $warranty
     * @return void
     */
    public function forceDeleted(Warranty $warranty)
    {
        //
    }
}
