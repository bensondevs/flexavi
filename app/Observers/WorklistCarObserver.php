<?php

namespace App\Observers;

use App\Models\{Worklist\WorklistCar};
use App\Services\Log\LogService;

class WorklistCarObserver
{
    /**
     * Handle the WorklistCar "creating" event.
     *
     * @param WorklistCar $worklistCar
     * @return void
     */
    public function creating(WorklistCar $worklistCar)
    {
        $worklistCar->id = generateUuid();
    }

    /**
     * Handle the WorklistCar "created" event.
     *
     * @param WorklistCar $worklistCar
     * @return void
     */
    public function created(WorklistCar $worklistCar)
    {
        if ($user = auth()->user())
            LogService::make("worklist_car.store")->by($worklistCar)->on($worklistCar)->write();
    }

    /**
     * Handle the WorklistCar "updated" event.
     *
     * @param WorklistCar $worklistCar
     * @return void
     */
    public function updated(WorklistCar $worklistCar)
    {
        if ($user = auth()->user()) {
            if ($worklistCar->isDirty('car_id'))
                LogService::make("worklist_car.updates.car_id")->by($user)->on($worklistCar)->write();

            if ($worklistCar->isDirty('employee_in_charge_id'))
                LogService::make("worklist_car.updates.employee_in_charge_id")->by($user)->on($worklistCar)->write();

            if ($worklistCar->isDirty('should_return_at'))
                LogService::make("worklist_car.updates.should_return_at")->by($user)->on($worklistCar)->write();

            if ($worklistCar->isDirty('returned_at'))
                LogService::make("worklist_car.updates.returned_at")->by($user)->on($worklistCar)->write();
        }
    }

    /**
     * Handle the WorklistCar "deleted" event.
     *
     * @param WorklistCar $worklistCar
     * @return void
     */
    public function deleted(WorklistCar $worklistCar)
    {
        if ($user = auth()->user())
            LogService::make("worklist_car.delete")->by($worklistCar)->on($worklistCar)->write();
    }

    /**
     * Handle the WorklistCar "restored" event.
     *
     * @param WorklistCar $worklistCar
     * @return void
     */
    public function restored(WorklistCar $worklistCar)
    {
        if ($user = auth()->user())
            LogService::make("worklist_car.restore")->by($worklistCar)->on($worklistCar)->write();
    }

    /**
     * Handle the WorklistCar "force deleted" event.
     *
     * @param WorklistCar $worklistCar
     * @return void
     */
    public function forceDeleted(WorklistCar $worklistCar)
    {
        if ($user = auth()->user())
            LogService::make("worklist_car.force_delete")->by($worklistCar)->on($worklistCar)->write();
    }
}
