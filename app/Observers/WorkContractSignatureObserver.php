<?php

namespace App\Observers;

use App\Models\WorkContract\WorkContractSignature;

class WorkContractSignatureObserver
{
    /**
     * Handle the WorkContractSignature "created" event.
     *
     * @param WorkContractSignature $workContractSignature
     * @return void
     */
    public function created(WorkContractSignature $workContractSignature): void
    {
        //
    }

    /**
     * Handle the WorkContractSignature "creating" event.
     *
     * @param WorkContractSignature $workContractSignature
     * @return void
     */
    public function creating(WorkContractSignature $workContractSignature): void
    {
        $workContractSignature->id = generateUuid();
    }

    /**
     * Handle the WorkContractSignature "updating" event.
     *
     * @param WorkContractSignature $workContractSignature
     * @return void
     */
    public function updating(WorkContractSignature $workContractSignature): void
    {
        //
    }

    /**
     * Handle the WorkContractSignature "updated" event.
     *
     * @param WorkContractSignature $workContractSignature
     * @return void
     */
    public function updated(WorkContractSignature $workContractSignature): void
    {
        //
    }

    /**
     * Handle the WorkContractSignature "deleted" event.
     *
     * @param WorkContractSignature $workContractSignature
     * @return void
     */
    public function deleted(WorkContractSignature $workContractSignature): void
    {
        //
    }

    /**
     * Handle the WorkContractSignature "restored" event.
     *
     * @param WorkContractSignature $workContractSignature
     * @return void
     */
    public function restored(WorkContractSignature $workContractSignature): void
    {
        //
    }

    /**
     * Handle the WorkContractSignature "force deleted" event.
     *
     * @param WorkContractSignature $workContractSignature
     * @return void
     */
    public function forceDeleted(WorkContractSignature $workContractSignature): void
    {
        //
    }
}
