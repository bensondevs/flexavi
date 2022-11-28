<?php

namespace App\Observers;

use App\Models\WorkContract\WorkContract;

class WorkContractObserver
{
    /**
     * Handle the WorkContract "created" event.
     *
     * @param WorkContract $workContract
     * @return void
     */
    public function created(WorkContract $workContract): void
    {
        //
    }

    /**
     * Handle the WorkContract "creating" event.
     *
     * @param WorkContract $workContract
     * @return void
     */
    public function creating(WorkContract $workContract): void
    {
        $workContract->id = generateUuid();
    }

    /**
     * Handle the WorkContract "updated" event.
     *
     * @param WorkContract $workContract
     * @return void
     */
    public function updated(WorkContract $workContract): void
    {
        //
    }

    /**
     * Handle the WorkContract "deleted" event.
     *
     * @param WorkContract $workContract
     * @return void
     */
    public function deleted(WorkContract $workContract): void
    {
        //
    }

    /**
     * Handle the WorkContract "restored" event.
     *
     * @param WorkContract $workContract
     * @return void
     */
    public function restored(WorkContract $workContract): void
    {
        //
    }

    /**
     * Handle the WorkContract "force deleted" event.
     *
     * @param WorkContract $workContract
     * @return void
     */
    public function forceDeleted(WorkContract $workContract): void
    {
        //
    }
}
