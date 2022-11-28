<?php

namespace App\Observers;

use App\Models\Warranty\WarrantyAppointment;

class WarrantyAppointmentObserver
{
    /**
     * Handle the WarrantyAppointment "creating" event.
     *
     * @param WarrantyAppointment $warrantyAppointment
     * @return void
     */
    public function creating(WarrantyAppointment $warrantyAppointment)
    {
        $warrantyAppointment->id = generateUuid();
    }

    /**
     * Handle the WarrantyAppointment "created" event.
     *
     * @param WarrantyAppointment $warrantyAppointment
     * @return void
     */
    public function created(WarrantyAppointment $warrantyAppointment)
    {
        //
    }

    /**
     * Handle the WarrantyAppointment "updated" event.
     *
     * @param WarrantyAppointment $warrantyAppointment
     * @return void
     */
    public function updated(WarrantyAppointment $warrantyAppointment)
    {
        //
    }

    /**
     * Handle the WarrantyAppointment "deleted" event.
     *
     * @param WarrantyAppointment $warrantyAppointment
     * @return void
     */
    public function deleted(WarrantyAppointment $warrantyAppointment)
    {
        //
    }

    /**
     * Handle the WarrantyAppointment "restored" event.
     *
     * @param WarrantyAppointment $warrantyAppointment
     * @return void
     */
    public function restored(WarrantyAppointment $warrantyAppointment)
    {
        //
    }

    /**
     * Handle the WarrantyAppointment "force deleted" event.
     *
     * @param WarrantyAppointment $warrantyAppointment
     * @return void
     */
    public function forceDeleted(WarrantyAppointment $warrantyAppointment)
    {
        //
    }
}
