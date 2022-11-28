<?php

namespace App\Observers;

use App\Models\Appointment\RelatedAppointment;

class RelatedAppointmentObserver
{
    /**
     * Handle the RelatedAppointment "created" event.
     *
     * @param RelatedAppointment $relatedAppointment
     * @return void
     */
    public function created(RelatedAppointment $relatedAppointment)
    {
        $relatedAppointment->id = generateUuid();
    }

    /**
     * Handle the RelatedAppointment "updated" event.
     *
     * @param RelatedAppointment $relatedAppointment
     * @return void
     */
    public function updated(RelatedAppointment $relatedAppointment)
    {
        //
    }

    /**
     * Handle the RelatedAppointment "deleted" event.
     *
     * @param RelatedAppointment $relatedAppointment
     * @return void
     */
    public function deleted(RelatedAppointment $relatedAppointment)
    {
        //
    }

    /**
     * Handle the RelatedAppointment "restored" event.
     *
     * @param RelatedAppointment $relatedAppointment
     * @return void
     */
    public function restored(RelatedAppointment $relatedAppointment)
    {
        //
    }

    /**
     * Handle the RelatedAppointment "force deleted" event.
     *
     * @param RelatedAppointment $relatedAppointment
     * @return void
     */
    public function forceDeleted(RelatedAppointment $relatedAppointment)
    {
        //
    }
}
