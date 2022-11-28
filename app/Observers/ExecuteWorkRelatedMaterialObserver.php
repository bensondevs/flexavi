<?php

namespace App\Observers;

use App\Models\ExecuteWork\ExecuteWorkRelatedMaterial;

class ExecuteWorkRelatedMaterialObserver
{
    /**
     * Handle the ExecuteWorkRelatedMaterial "creating" event.
     *
     * @param ExecuteWorkRelatedMaterial $executeWorkRelatedMaterial
     * @return void
     */
    public function creating(ExecuteWorkRelatedMaterial $executeWorkRelatedMaterial)
    {
        $executeWorkRelatedMaterial->id = generateUuid();
    }

    /**
     * Handle the ExecuteWorkRelatedMaterial "created" event.
     *
     * @param ExecuteWorkRelatedMaterial $executeWorkRelatedMaterial
     * @return void
     */
    public function created(ExecuteWorkRelatedMaterial $executeWorkRelatedMaterial)
    {
        //
    }

    /**
     * Handle the ExecuteWorkRelatedMaterial "updated" event.
     *
     * @param ExecuteWorkRelatedMaterial $executeWorkRelatedMaterial
     * @return void
     */
    public function updated(ExecuteWorkRelatedMaterial $executeWorkRelatedMaterial)
    {
        //
    }

    /**
     * Handle the ExecuteWorkRelatedMaterial "deleted" event.
     *
     * @param ExecuteWorkRelatedMaterial $executeWorkRelatedMaterial
     * @return void
     */
    public function deleted(ExecuteWorkRelatedMaterial $executeWorkRelatedMaterial)
    {
        //
    }

    /**
     * Handle the ExecuteWorkRelatedMaterial "restored" event.
     *
     * @param ExecuteWorkRelatedMaterial $executeWorkRelatedMaterial
     * @return void
     */
    public function restored(ExecuteWorkRelatedMaterial $executeWorkRelatedMaterial)
    {
        //
    }

    /**
     * Handle the ExecuteWorkRelatedMaterial "force deleted" event.
     *
     * @param ExecuteWorkRelatedMaterial $executeWorkRelatedMaterial
     * @return void
     */
    public function forceDeleted(ExecuteWorkRelatedMaterial $executeWorkRelatedMaterial)
    {
        //
    }
}
