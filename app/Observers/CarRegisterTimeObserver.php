<?php

namespace App\Observers;

use App\Models\Car\CarRegisterTime;
use App\Services\Log\LogService;

class CarRegisterTimeObserver
{
    /**
     * Handle the CarRegisterTime "created" event.
     *
     * @param CarRegisterTime $carRegisterTime
     * @return void
     */
    public function created(CarRegisterTime $carRegisterTime)
    {
        if ($user = auth()->user())
            LogService::make("car_register_time.store")->by($user)->on($carRegisterTime)->write();
    }

    /**
     * Handle the CarRegisterTime "updating" event.
     *
     * @param CarRegisterTime $carRegisterTime
     * @return void
     */
    public function updating(CarRegisterTime $carRegisterTime)
    {
        session()->put("props.old.customer", $carRegisterTime->getOriginal());
    }

    /**
     * Handle the CarRegisterTime "updated" event.
     *
     * @param CarRegisterTime $carRegisterTime
     * @return void
     */
    public function updated(CarRegisterTime $carRegisterTime)
    {
        if ($carRegisterTime->isDirty('marked_out_at') && ($carRegisterTime->getOriginal('marked_out_at') == null)) {
            $car = $carRegisterTime->car;
            $car->setOut();
        }

        if ($carRegisterTime->isDirty('marked_return_at') && ($carRegisterTime->getOriginal('marked_return_at') == null)) {
            $car = $carRegisterTime->car;
            $car->setFree();
        }

        if ($user = auth()->user()) {
            if ($car->isDirty("worklist_id"))
                LogService::make("car_register_time.updates.worklist_id")->by($user)->on($carRegisterTime)->write();
            if ($car->isDirty("car_id"))
                LogService::make("car_register_time.updates.car_id")->by($user)->on($carRegisterTime)->write();
            if ($car->isDirty("should_out_at"))
                LogService::make("car_register_time.updates.should_out_at")->by($user)->on($carRegisterTime)->write();
            if ($car->isDirty("should_return_at"))
                LogService::make("car_register_time.updates.should_return_at")->by($user)->on($carRegisterTime)->write();
            if ($car->isDirty("marked_out_at"))
                LogService::make("car_register_time.updates.marked_out_at")->by($user)->on($carRegisterTime)->write();
            if ($car->isDirty("marked_return_at"))
                LogService::make("car_register_time.updates.marked_return_at")->by($user)->on($carRegisterTime)->write();
        }

        session()->forget("props.old.customer");
    }

    /**
     * Handle the CarRegisterTime "deleted" event.
     *
     * @param CarRegisterTime $carRegisterTime
     * @return void
     */
    public function deleted(CarRegisterTime $carRegisterTime)
    {
        if (!$carRegisterTime->marked_return_at) {
            $car = $carRegisterTime->car;
            $car->setFree();
        }

        if ($user = auth()->user())
            LogService::make("car_register_time.delete")->by($user)->on($carRegisterTime)->write();
    }

    /**
     * Handle the CarRegisterTime "restored" event.
     *
     * @param CarRegisterTime $carRegisterTime
     * @return void
     */
    public function restored(CarRegisterTime $carRegisterTime)
    {
        if ($user = auth()->user())
            LogService::make("car_register_time.restore")->by($user)->on($carRegisterTime)->write();
    }

    /**
     * Handle the CarRegisterTime "force deleted" event.
     *
     * @param CarRegisterTime $carRegisterTime
     * @return void
     */
    public function forceDeleted(CarRegisterTime $carRegisterTime)
    {
        if (!$carRegisterTime->marked_return_at) {
            $car = $carRegisterTime->car;
            $car->setFree();
        }

        if ($user = auth()->user())
            LogService::make("car_register_time.force_delete")->by($user)->on($carRegisterTime)->write();
    }
}
