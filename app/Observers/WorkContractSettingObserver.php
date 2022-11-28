<?php

namespace App\Observers;

use App\Models\Setting\WorkContractSetting;

class WorkContractSettingObserver
{
    /**
     * Handle the WorkContractSetting "creating" event.
     *
     * @param WorkContractSetting $workContractSetting
     * @return void
     */
    public function creating(WorkContractSetting $workContractSetting): void
    {
        $workContractSetting->id = generateUuid();
    }

    /**
     * Handle the WorkContractSetting "created" event.
     *
     * @param WorkContractSetting $workContractSetting
     * @return void
     */
    public function created(WorkContractSetting $workContractSetting): void
    {
        //
    }

    /**
     * Handle the WorkContractSetting "updated" event.
     *
     * @param WorkContractSetting $workContractSetting
     * @return void
     */
    public function updated(WorkContractSetting $workContractSetting): void
    {
        //
    }

    /**
     * Handle the WorkContractSetting "updating" event.
     *
     * @param WorkContractSetting $workContractSetting
     * @return void
     */
    public function updating(WorkContractSetting $workContractSetting): void
    {
        //
    }

    /**
     * Handle the WorkContractSetting "deleted" event.
     *
     * @param WorkContractSetting $workContractSetting
     * @return void
     */
    public function deleted(WorkContractSetting $workContractSetting): void
    {
        //
    }

    /**
     * Handle the WorkContractSetting "restored" event.
     *
     * @param WorkContractSetting $workContractSetting
     * @return void
     */
    public function restored(WorkContractSetting $workContractSetting): void
    {
        //
    }

    /**
     * Handle the WorkContractSetting "force deleted" event.
     *
     * @param WorkContractSetting $workContractSetting
     * @return void
     */
    public function forceDeleted(WorkContractSetting $workContractSetting): void
    {
        //
    }
}
