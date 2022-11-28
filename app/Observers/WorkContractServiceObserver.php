<?php

namespace App\Observers;

use App\Models\WorkContract\WorkContractService;

class WorkContractServiceObserver
{
    /**
     * Handle the WorkContractService "created" event.
     *
     * @param WorkContractService $workContractService
     * @return void
     */
    public function created(WorkContractService $workContractService): void
    {
        //
    }

    /**
     * Handle the WorkContractService "creating" event.
     *
     * @param WorkContractService $workContractService
     * @return void
     */
    public function creating(WorkContractService $workContractService): void
    {
        $workContractService->id = generateUuid();
    }

    /**
     * Handle the WorkContractService "updated" event.
     *
     * @param WorkContractService $workContractService
     * @return void
     */
    public function updated(WorkContractService $workContractService): void
    {
        //
    }

    /**
     * Handle the WorkContractService "deleted" event.
     *
     * @param WorkContractService $workContractService
     * @return void
     */
    public function deleted(WorkContractService $workContractService): void
    {
        //
    }

    /**
     * Handle the WorkContractService "restored" event.
     *
     * @param WorkContractService $workContractService
     * @return void
     */
    public function restored(WorkContractService $workContractService): void
    {
        //
    }

    /**
     * Handle the WorkContractService "force deleted" event.
     *
     * @param WorkContractService $workContractService
     * @return void
     */
    public function forceDeleted(WorkContractService $workContractService): void
    {
        //
    }
}
