<?php

namespace App\Observers;

use App\Models\Setting\WorkContractSignatureSetting;

class WorkContractSignatureSettingObserver
{
    /**
     * Handle the WorkContractSignatureSetting "creating" event.
     *
     * @param WorkContractSignatureSetting $workContractSignatureSetting
     * @return void
     */
    public function creating(WorkContractSignatureSetting $workContractSignatureSetting): void
    {
        $workContractSignatureSetting->id = generateUuid();
    }

    /**
     * Handle the WorkContractSignatureSetting "created" event.
     *
     * @param WorkContractSignatureSetting $workContractSignatureSetting
     * @return void
     */
    public function created(WorkContractSignatureSetting $workContractSignatureSetting): void
    {
        //
    }

    /**
     * Handle the WorkContractSignatureSetting "updated" event.
     *
     * @param WorkContractSignatureSetting $workContractSignatureSetting
     * @return void
     */
    public function updated(WorkContractSignatureSetting $workContractSignatureSetting): void
    {
        //
    }

    /**
     * Handle the WorkContractSignatureSetting "deleted" event.
     *
     * @param WorkContractSignatureSetting $workContractSignatureSetting
     * @return void
     */
    public function deleted(WorkContractSignatureSetting $workContractSignatureSetting): void
    {
        //
    }

    /**
     * Handle the WorkContractSignatureSetting "restored" event.
     *
     * @param WorkContractSignatureSetting $workContractSignatureSetting
     * @return void
     */
    public function restored(WorkContractSignatureSetting $workContractSignatureSetting): void
    {
        //
    }

    /**
     * Handle the WorkContractSignatureSetting "force deleted" event.
     *
     * @param WorkContractSignatureSetting $workContractSignatureSetting
     * @return void
     */
    public function forceDeleted(WorkContractSignatureSetting $workContractSignatureSetting): void
    {
        //
    }
}
