<?php

namespace App\Observers;

use App\Models\CarRegisterTime;

class CarRegisterTimeObserver
{
    /**
     * Handle the CarRegisterTime "created" event.
     *
     * @param  \App\Models\CarRegisterTime  $time
     * @return void
     */
    public function created(CarRegisterTime $time)
    {
        //
    }

    /**
     * Handle the CarRegisterTime "updated" event.
     *
     * @param  \App\Models\CarRegisterTime  $time
     * @return void
     */
    public function updated(CarRegisterTime $time)
    {
        if ($time->isDirty('marked_out_at') && ($time->getOriginal('marked_out_at') == null)) {
            $car = $time->car;
            $car->setOut();
        }

        if ($time->isDirty('marked_return_at') && ($time->getOriginal('marked_return_at') == null)) {
            $car = $time->car;
            $car->setFree();
        }
    }

    /**
     * Handle the CarRegisterTime "deleted" event.
     *
     * @param  \App\Models\CarRegisterTime  $time
     * @return void
     */
    public function deleted(CarRegisterTime $time)
    {
        if (! $time->marked_return_at) {
            $car = $time->car;
            $car->setFree();
        }
    }

    /**
     * Handle the CarRegisterTime "restored" event.
     *
     * @param  \App\Models\CarRegisterTime  $time
     * @return void
     */
    public function restored(CarRegisterTime $time)
    {
        //
    }

    /**
     * Handle the CarRegisterTime "force deleted" event.
     *
     * @param  \App\Models\CarRegisterTime  $time
     * @return void
     */
    public function forceDeleted(CarRegisterTime $time)
    {
        if (! $time->marked_return_at) {
            $car = $time->car;
            $car->setFree();
        }
    }
}
