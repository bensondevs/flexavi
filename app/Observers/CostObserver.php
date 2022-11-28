<?php

namespace App\Observers;

use App\Models\Cost\Cost;

class CostObserver
{
    /**
     * Handle the Cost "created" event.
     *
     * @param Cost $cost
     * @return void
     */
    public function created(Cost $cost)
    {
        //
    }

    /**
     * Handle the Cost "updated" event.
     *
     * @param Cost $cost
     * @return void
     */
    public function updated(Cost $cost)
    {
        //
    }

    /**
     * Handle the Cost "deleted" event.
     *
     * @param Cost $cost
     * @return void
     */
    public function deleted(Cost $cost)
    {
        //
    }

    /**
     * Handle the Cost "restored" event.
     *
     * @param Cost $cost
     * @return void
     */
    public function restored(Cost $cost)
    {
        //
    }

    /**
     * Handle the Cost "force deleted" event.
     *
     * @param Cost $cost
     * @return void
     */
    public function forceDeleted(Cost $cost)
    {
        //
    }
}
