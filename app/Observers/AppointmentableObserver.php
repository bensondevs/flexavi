<?php

namespace App\Observers;

use App\Models\Appointmentable;

class AppointmentableObserver
{
    /**
     * Handle the Appointmentable "created" event.
     *
     * @param  \App\Models\Appointmentable  $appointmentable
     * @return void
     */
    public function created(Appointmentable $appointmentable)
    {
        if ($appointmentable->order_index === null) {
            $appointmentable->setOrderIndex();
            $appointmentable->save();
        }
    }

    /**
     * Handle the Appointmentable "updated" event.
     *
     * @param  \App\Models\Appointmentable  $appointmentable
     * @return void
     */
    public function updated(Appointmentable $appointmentable)
    {
        if ($appointmentable->isDirty('order_index')) {
            $appointmentable->reorderIndex();
        }
    }

    /**
     * Handle the Appointmentable "deleted" event.
     *
     * @param  \App\Models\Appointmentable  $appointmentable
     * @return void
     */
    public function deleted(Appointmentable $appointmentable)
    {
        $appointmentable->reorderIndex();
    }

    /**
     * Handle the Appointmentable "restored" event.
     *
     * @param  \App\Models\Appointmentable  $appointmentable
     * @return void
     */
    public function restored(Appointmentable $appointmentable)
    {
        $appointmentable->reorderIndex();
    }

    /**
     * Handle the Appointmentable "force deleted" event.
     *
     * @param  \App\Models\Appointmentable  $appointmentable
     * @return void
     */
    public function forceDeleted(Appointmentable $appointmentable)
    {
        $appointmentable->reorderIndex();
    }
}
