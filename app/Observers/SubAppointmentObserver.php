<?php

namespace App\Observers;

use App\Models\Appointment\SubAppointment;

class SubAppointmentObserver
{
    /**
     * Handle the SubAppointment "created" event.
     *
     * @param SubAppointment $subAppointment
     * @return void
     */
    public function created(SubAppointment $subAppointment)
    {
        if ($subAppointment->previous_sub_appointment_id) {
            $previousSubAppointment = $subAppointment->previousSubAppointment()->getChild();
            $previousSubAppointment->rescheduled_sub_appointment_id = $subAppointment->id;
            $previousSubAppointment->save();
        }
    }

    /**
     * Handle the SubAppointment "updated" event.
     *
     * @param SubAppointment $subAppointment
     * @return void
     */
    public function updated(SubAppointment $subAppointment)
    {
        //
    }

    /**
     * Handle the SubAppointment "deleted" event.
     *
     * @param SubAppointment $subAppointment
     * @return void
     */
    public function deleted(SubAppointment $subAppointment)
    {
        //
    }

    /**
     * Handle the SubAppointment "restored" event.
     *
     * @param SubAppointment $subAppointment
     * @return void
     */
    public function restored(SubAppointment $subAppointment)
    {
        //
    }

    /**
     * Handle the SubAppointment "force deleted" event.
     *
     * @param SubAppointment $subAppointment
     * @return void
     */
    public function forceDeleted(SubAppointment $subAppointment)
    {
        //
    }
}
