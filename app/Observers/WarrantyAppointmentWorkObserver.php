<?php

namespace App\Observers;

use App\Models\Warranty\WarrantyAppointmentWork;

class WarrantyAppointmentWorkObserver
{
    /**
     * Handle the WarrantyAppointmentWork "creating" event.
     *
     * @param WarrantyAppointmentWork $warrantyAppointmentWork
     * @return void
     */
    public function creating(WarrantyAppointmentWork $warrantyAppointmentWork)
    {
        $warrantyAppointmentWork->id = generateUuid();
    }

    /**
     * Handle the WarrantyAppointmentWork "created" event.
     *
     * @param WarrantyAppointmentWork $warrantyAppointmentWork
     * @return void
     */
    public function created(WarrantyAppointmentWork $warrantyAppointmentWork)
    {
        //
    }

    /**
     * Handle the WarrantyAppointmentWork "updated" event.
     *
     * @param WarrantyAppointmentWork $warrantyAppointmentWork
     * @return void
     */
    public function updated(WarrantyAppointmentWork $warrantyAppointmentWork)
    {
        //
    }

    /**
     * Handle the WarrantyAppointmentWork "deleted" event.
     *
     * @param WarrantyAppointmentWork $warrantyAppointmentWork
     * @return void
     */
    public function deleted(WarrantyAppointmentWork $warrantyAppointmentWork)
    {
        //
    }

    /**
     * Handle the WarrantyAppointmentWork "restored" event.
     *
     * @param WarrantyAppointmentWork $warrantyAppointmentWork
     * @return void
     */
    public function restored(WarrantyAppointmentWork $warrantyAppointmentWork)
    {
        //
    }

    /**
     * Handle the WarrantyAppointmentWork "force deleted" event.
     *
     * @param WarrantyAppointmentWork $warrantyAppointmentWork
     * @return void
     */
    public function forceDeleted(WarrantyAppointmentWork $warrantyAppointmentWork)
    {
        //
    }
}
