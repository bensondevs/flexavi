<?php

namespace App\Observers;

use App\Models\Permission\ModulePermission;

class ModulePermissionObserver
{
    /**
     * Handle the ModulePermission "creating" event.
     *
     * @param ModulePermission $modulePermission
     * @return void
     */
    public function creating(ModulePermission $modulePermission): void
    {
        $modulePermission->id = generateUuid();
    }

    /**
     * Handle the ModulePermission "created" event.
     *
     * @param ModulePermission $modulePermission
     * @return void
     */
    public function created(ModulePermission $modulePermission): void
    {
        //
    }

    /**
     * Handle the ModulePermission "updated" event.
     *
     * @param ModulePermission $modulePermission
     * @return void
     */
    public function updated(ModulePermission $modulePermission): void
    {
        //
    }

    /**
     * Handle the ModulePermission "deleted" event.
     *
     * @param ModulePermission $modulePermission
     * @return void
     */
    public function deleted(ModulePermission $modulePermission): void
    {
        //
    }

    /**
     * Handle the ModulePermission "restored" event.
     *
     * @param ModulePermission $modulePermission
     * @return void
     */
    public function restored(ModulePermission $modulePermission): void
    {
        //
    }

    /**
     * Handle the ModulePermission "force deleted" event.
     *
     * @param ModulePermission $modulePermission
     * @return void
     */
    public function forceDeleted(ModulePermission $modulePermission): void
    {
        //
    }
}
