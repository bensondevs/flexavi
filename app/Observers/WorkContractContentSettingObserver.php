<?php

namespace App\Observers;

use App\Models\Setting\WorkContractContentSetting;

class WorkContractContentSettingObserver
{
    /**
     * Handle the WorkContractContentSetting "created" event.
     *
     * @param WorkContractContentSetting $workContractContentSetting
     * @return void
     */
    public function created(WorkContractContentSetting $workContractContentSetting): void
    {
        //
    }

    /**
     * Handle the WorkContractContentSetting "creating" event.
     *
     * @param WorkContractContentSetting $workContractContentSetting
     * @return void
     */
    public function creating(WorkContractContentSetting $workContractContentSetting): void
    {
        $workContractContentSetting->id = generateUuid();
    }

    /**
     * Handle the WorkContractContentSetting "updated" event.
     *
     * @param WorkContractContentSetting $workContractContentSetting
     * @return void
     */
    public function updated(WorkContractContentSetting $workContractContentSetting): void
    {
        //
    }

    /**
     * Handle the WorkContractContentSetting "deleted" event.
     *
     * @param WorkContractContentSetting $workContractContentSetting
     * @return void
     */
    public function deleted(WorkContractContentSetting $workContractContentSetting): void
    {
        //
    }

    /**
     * Handle the WorkContractContentSetting "restored" event.
     *
     * @param WorkContractContentSetting $workContractContentSetting
     * @return void
     */
    public function restored(WorkContractContentSetting $workContractContentSetting): void
    {
        //
    }

    /**
     * Handle the WorkContractContentSetting "force deleted" event.
     *
     * @param WorkContractContentSetting $workContractContentSetting
     * @return void
     */
    public function forceDeleted(WorkContractContentSetting $workContractContentSetting): void
    {
        //
    }
}
