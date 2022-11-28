<?php

namespace App\Observers;

use App\Models\Inspection\InspectionPicture;

class InspectionPictureObserver
{
    /**
     * Handle the InspectionPicture "creating" event.
     *
     * @param InspectionPicture $inspectionPicture
     * @return void
     */
    public function creating(InspectionPicture $inspectionPicture)
    {
        $inspectionPicture->id = generateUuid();
    }

    /**
     * Handle the InspectionPicture "created" event.
     *
     * @param InspectionPicture $inspectionPicture
     * @return void
     */
    public function created(InspectionPicture $inspectionPicture)
    {
        //
    }

    /**
     * Handle the InspectionPicture "updated" event.
     *
     * @param InspectionPicture $inspectionPicture
     * @return void
     */
    public function updated(InspectionPicture $inspectionPicture)
    {
        //
    }

    /**
     * Handle the InspectionPicture "deleted" event.
     *
     * @param InspectionPicture $inspectionPicture
     * @return void
     */
    public function deleted(InspectionPicture $inspectionPicture)
    {
        //
    }

    /**
     * Handle the InspectionPicture "restored" event.
     *
     * @param InspectionPicture $inspectionPicture
     * @return void
     */
    public function restored(InspectionPicture $inspectionPicture)
    {
        //
    }

    /**
     * Handle the InspectionPicture "force deleted" event.
     *
     * @param InspectionPicture $inspectionPicture
     * @return void
     */
    public function forceDeleted(InspectionPicture $inspectionPicture)
    {
        //
    }
}
