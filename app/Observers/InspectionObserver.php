<?php

namespace App\Observers;

use App\Models\Inspection\Inspection;

class InspectionObserver
{
    /**
     * Handle the Inspection "creating" event.
     *
     * @param Inspection $inspection
     * @return void
     */
    public function creating(Inspection $inspection)
    {
        $inspection->id = generateUuid();
    }

    /**
     * Handle the Inspection "created" event.
     *
     * @param Inspection $inspection
     * @return void
     */
    public function created(Inspection $inspection)
    {
        //
    }

    /**
     * Handle the Inspection "updated" event.
     *
     * @param Inspection $inspection
     * @return void
     */
    public function updated(Inspection $inspection)
    {
        //
    }

    /**
     * Handle the Inspection "deleted" event.
     *
     * @param Inspection $inspection
     * @return void
     */
    public function deleted(Inspection $inspection)
    {
        //
    }

    /**
     * Handle the Inspection "restored" event.
     *
     * @param Inspection $inspection
     * @return void
     */
    public function restored(Inspection $inspection)
    {
        //
    }

    /**
     * Handle the Inspection "force deleted" event.
     *
     * @param Inspection $inspection
     * @return void
     */
    public function forceDeleted(Inspection $inspection)
    {
        //
    }
}
