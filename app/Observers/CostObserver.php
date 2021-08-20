<?php

namespace App\Observers;

use App\Models\Cost;

use App\Models\Workday;
use App\Models\Worklist;
use App\Models\Appointment;

class CostObserver
{
    /**
     * Handle the Cost "created" event.
     *
     * @param  \App\Models\Cost  $cost
     * @return void
     */
    public function created(Cost $cost)
    {
        //
    }

    /**
     * Handle the Cost "updated" event.
     *
     * @param  \App\Models\Cost  $cost
     * @return void
     */
    public function updated(Cost $cost)
    {
        //
    }

    /**
     * Handle the Cost "deleted" event.
     *
     * @param  \App\Models\Cost  $cost
     * @return void
     */
    public function deleted(Cost $cost)
    {
        //
    }

    /**
     * Handle the Cost "restored" event.
     *
     * @param  \App\Models\Cost  $cost
     * @return void
     */
    public function restored(Cost $cost)
    {
        //
    }

    /**
     * Handle the Cost "force deleted" event.
     *
     * @param  \App\Models\Cost  $cost
     * @return void
     */
    public function forceDeleted(Cost $cost)
    {
        //
    }
}
