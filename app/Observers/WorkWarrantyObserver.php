<?php

namespace App\Observers;

use App\Models\ExecuteWork\WorkWarranty;

class WorkWarrantyObserver
{
    /**
     * Handle the WorkWarranty "creating" event.
     *
     * @param WorkWarranty $workWarranty
     * @return void
     */
    public function creating(WorkWarranty $workWarranty)
    {
        $workWarranty->id = generateUuid();
    }

    /**
     * Handle the WorkWarranty "created" event.
     *
     * @param WorkWarranty $workWarranty
     * @return void
     */
    public function created(WorkWarranty $workWarranty)
    {
        //
    }

    /**
     * Handle the WorkWarranty "updated" event.
     *
     * @param WorkWarranty $workWarranty
     * @return void
     */
    public function updated(WorkWarranty $workWarranty)
    {
        //
    }

    /**
     * Handle the WorkWarranty "deleted" event.
     *
     * @param WorkWarranty $workWarranty
     * @return void
     */
    public function deleted(WorkWarranty $workWarranty)
    {
        //
    }

    /**
     * Handle the WorkWarranty "restored" event.
     *
     * @param WorkWarranty $workWarranty
     * @return void
     */
    public function restored(WorkWarranty $workWarranty)
    {
        //
    }

    /**
     * Handle the WorkWarranty "force deleted" event.
     *
     * @param WorkWarranty $workWarranty
     * @return void
     */
    public function forceDeleted(WorkWarranty $workWarranty)
    {
        //
    }
}
