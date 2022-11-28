<?php

namespace App\Observers;

use App\Models\WorkContract\WorkContractContent;

class WorkContractContentObserver
{
    /**
     * Handle the WorkContractContent "creating" event.
     *
     * @param WorkContractContent $workContractContent
     * @return void
     */
    public function creating(WorkContractContent $workContractContent): void
    {
        $workContractContent->id = generateUuid();
    }

    /**
     * Handle the WorkContractContent "created" event.
     *
     * @param WorkContractContent $workContractContent
     * @return void
     */
    public function created(WorkContractContent $workContractContent): void
    {
        //
    }

    /**
     * Handle the WorkContractContent "updated" event.
     *
     * @param WorkContractContent $workContractContent
     * @return void
     */
    public function updated(WorkContractContent $workContractContent): void
    {
        //
    }

    /**
     * Handle the WorkContractContent "deleted" event.
     *
     * @param WorkContractContent $workContractContent
     * @return void
     */
    public function deleted(WorkContractContent $workContractContent): void
    {
        //
    }

    /**
     * Handle the WorkContractContent "restored" event.
     *
     * @param WorkContractContent $workContractContent
     * @return void
     */
    public function restored(WorkContractContent $workContractContent): void
    {
        //
    }

    /**
     * Handle the WorkContractContent "force deleted" event.
     *
     * @param WorkContractContent $workContractContent
     * @return void
     */
    public function forceDeleted(WorkContractContent $workContractContent): void
    {
        //
    }
}
