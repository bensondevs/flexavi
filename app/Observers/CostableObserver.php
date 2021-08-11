<?php

namespace App\Observers;

use App\Models\Costable;
use App\Models\Workday;
use App\Models\Worklist;
use App\Models\Appointment;

class CostableObserver
{
    /**
     * Handle the Costable "created" event.
     *
     * @param  \App\Models\Costable  $costable
     * @return void
     */
    public function created(Costable $costable)
    {
        //
    }

    /**
     * Handle the Costable "updated" event.
     *
     * @param  \App\Models\Costable  $costable
     * @return void
     */
    public function updated(Costable $costable)
    {
        //
    }

    /**
     * Handle the Costable "deleted" event.
     *
     * @param  \App\Models\Costable  $costable
     * @return void
     */
    public function deleted(Costable $costable)
    {
        //
    }

    /**
     * Handle the Costable "restored" event.
     *
     * @param  \App\Models\Costable  $costable
     * @return void
     */
    public function restored(Costable $costable)
    {
        //
    }

    /**
     * Handle the Costable "force deleted" event.
     *
     * @param  \App\Models\Costable  $costable
     * @return void
     */
    public function forceDeleted(Costable $costable)
    {
        //
    }
}
