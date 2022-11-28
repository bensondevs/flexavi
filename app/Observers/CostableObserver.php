<?php

namespace App\Observers;

use App\Models\Cost\Costable;

class CostableObserver
{
    /**
     * Handle the Costable "created" event.
     *
     * @param Costable $costable
     * @return void
     */
    public function created(Costable $costable)
    {
        //
    }

    /**
     * Handle the Costable "updated" event.
     *
     * @param Costable $costable
     * @return void
     */
    public function updated(Costable $costable)
    {
        //
    }

    /**
     * Handle the Costable "deleted" event.
     *
     * @param Costable $costable
     * @return void
     */
    public function deleted(Costable $costable)
    {
        //
    }

    /**
     * Handle the Costable "restored" event.
     *
     * @param Costable $costable
     * @return void
     */
    public function restored(Costable $costable)
    {
        //
    }

    /**
     * Handle the Costable "force deleted" event.
     *
     * @param Costable $costable
     * @return void
     */
    public function forceDeleted(Costable $costable)
    {
        //
    }
}
