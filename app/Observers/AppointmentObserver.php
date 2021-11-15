<?php

namespace App\Observers;

use App\Models\{ Workday, Appointment };

class AppointmentObserver
{
    /**
     * Handle the Appointment "created" event.
     *
     * @param  \App\Models\Appointment  $appointment
     * @return void
     */
    public function created(Appointment $appointment)
    {
        $appointment->syncWorkdays();
    }

    /**
     * Handle the Appointment "updated" event.
     *
     * @param  \App\Models\Appointment  $appointment
     * @return void
     */
    public function updated(Appointment $appointment)
    {
        if ($appointment->isDirty('start') || $appointment->isDirty('end')) {
            $appointment->syncWorkdays();
        }
    }

    /**
     * Handle the Appointment "executed" event.
     *
     * @param  \App\Models\Appointment  $appointment
     * @return void
     */
    public function executed(Appointment $appointment)
    {
        //
    }

    /**
     * Handle the Appointment "processed" event.
     *
     * @param  \App\Models\Appointment  $appointment
     * @return void
     */
    public function processed(Appointment $appointment)
    {
        //
    }

    /**
     * Handle the Appointment "cancelled" event.
     *
     * @param  \App\Models\Appointment  $appointment
     * @return void
     */
    public function cancelled(Appointment $appointment)
    {
        //
    }

    /**
     * Handle the Appointment "deleted" event.
     *
     * @param  \App\Models\Appointment  $appointment
     * @return void
     */
    public function deleted(Appointment $appointment)
    {
        //
    }

    /**
     * Handle the Appointment "restored" event.
     *
     * @param  \App\Models\Appointment  $appointment
     * @return void
     */
    public function restored(Appointment $appointment)
    {
        //
    }

    /**
     * Handle the Appointment "force deleted" event.
     *
     * @param  \App\Models\Appointment  $appointment
     * @return void
     */
    public function forceDeleted(Appointment $appointment)
    {
        //
    }
}
