<?php

namespace App\Observers;

use App\Models\Customer\CustomerNote;

class CustomerNoteObserver
{
    /**
     * Handle the CustomerNote "creating" event.
     *
     * @param CustomerNote $customerNote
     * @return void
     */
    public function creating(CustomerNote $customerNote): void
    {
        $customerNote->id = generateUuid();
    }

    /**
     * Handle the CustomerNote "created" event.
     *
     * @param CustomerNote $customerNote
     * @return void
     */
    public function created(CustomerNote $customerNote): void
    {
        //
    }

    /**
     * Handle the CustomerNote "updated" event.
     *
     * @param CustomerNote $customerNote
     * @return void
     */
    public function updated(CustomerNote $customerNote): void
    {
        //
    }

    /**
     * Handle the CustomerNote "deleted" event.
     *
     * @param CustomerNote $customerNote
     * @return void
     */
    public function deleted(CustomerNote $customerNote): void
    {
        //
    }

    /**
     * Handle the CustomerNote "restored" event.
     *
     * @param CustomerNote $customerNote
     * @return void
     */
    public function restored(CustomerNote $customerNote): void
    {
        //
    }

    /**
     * Handle the CustomerNote "force deleted" event.
     *
     * @param CustomerNote $customerNote
     * @return void
     */
    public function forceDeleted(CustomerNote $customerNote): void
    {
        //
    }
}
