<?php

namespace App\Observers;

use App\Models\Worklist\WorklistEmployee;
use App\Services\Log\LogService;

class WorklistEmployeeObserver
{
    /**
     * Handle the WorklistEmployee "creating" event.
     *
     * @param WorklistEmployee $worklistEmployee
     * @return void
     */
    public function creating(WorklistEmployee $worklistEmployee)
    {
        $worklistEmployee->id = generateUuid();
    }

    /**
     * Handle the WorklistEmployee "created" event.
     *
     * @param WorklistEmployee $worklistEmployee
     * @return void
     */
    public function created(WorklistEmployee $worklistEmployee)
    {
        if ($user = auth()->user())
            LogService::make("worklist_employee.store")->by($user)->on($worklistEmployee)->write();
    }

    /**
     * Handle the WorklistEmployee "updated" event.
     *
     * @param WorklistEmployee $worklistEmployee
     * @return void
     */
    public function updated(WorklistEmployee $worklistEmployee)
    {
        if ($user = auth()->user()) {
            LogService::make("worklist_employee.update")->by($user)->on($worklistEmployee)->write();
        }
    }

    /**
     * Handle the WorklistEmployee "deleted" event.
     *
     * @param WorklistEmployee $worklistEmployee
     * @return void
     */
    public function deleted(WorklistEmployee $worklistEmployee)
    {
        if ($user = auth()->user())
            LogService::make("worklist_employee.delete")->by($user)->on($worklistEmployee)->write();
    }

    /**
     * Handle the WorklistEmployee "restored" event.
     *
     * @param WorklistEmployee $worklistEmployee
     * @return void
     */
    public function restored(WorklistEmployee $worklistEmployee)
    {
        if ($user = auth()->user())
            LogService::make("worklist_employee.restore")->by($user)->on($worklistEmployee)->write();
    }

    /**
     * Handle the WorklistEmployee "force deleted" event.
     *
     * @param WorklistEmployee $worklistEmployee
     * @return void
     */
    public function forceDeleted(WorklistEmployee $worklistEmployee)
    {
        if ($user = auth()->user())
            LogService::make("worklist_employee.force_delete")->by($user)->on($worklistEmployee)->write();
    }
}
