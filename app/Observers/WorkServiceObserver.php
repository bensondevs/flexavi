<?php

namespace App\Observers;

use App\Models\WorkService\WorkService;
use App\Services\WorkService\WorkServiceRelationService;

class WorkServiceObserver
{
    /**
     * Handle the WorkService "creating" event.
     *
     * @param WorkService $workService
     * @return void
     */
    public function creating(WorkService $workService): void
    {
        $workService->id = generateUuid();
    }

    /**
     * Handle the WorkService "created" event.
     *
     * @param WorkService $workService
     * @return void
     */
    public function created(WorkService $workService): void
    {
        //
    }

    /**
     * Handle the WorkService "updated" event.
     *
     * @param WorkService $workService
     * @return void
     */
    public function updated(WorkService $workService): void
    {
        app(WorkServiceRelationService::class)->dataChanged($workService);
    }

    /**
     * Handle the WorkService "deleted" event.
     *
     * @param WorkService $workService
     * @return void
     */
    public function deleted(WorkService $workService): void
    {
        //
    }

    /**
     * Handle the WorkService "restored" event.
     *
     * @param WorkService $workService
     * @return void
     */
    public function restored(WorkService $workService): void
    {
        //
    }

    /**
     * Handle the WorkService "force deleted" event.
     *
     * @param WorkService $workService
     * @return void
     */
    public function forceDeleted(WorkService $workService): void
    {
        //
    }
}
