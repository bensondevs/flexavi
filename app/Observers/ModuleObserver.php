<?php

namespace App\Observers;

use App\Models\Permission\Module;

class ModuleObserver
{
    /**
     * Handle the Module "creating" event.
     *
     * @param Module $module
     * @return void
     */
    public function creating(Module $module): void
    {
        $module->id = generateUuid();
    }

    /**
     * Handle the Module "created" event.
     *
     * @param Module $module
     * @return void
     */
    public function created(Module $module): void
    {
        //
    }

    /**
     * Handle the Module "updated" event.
     *
     * @param Module $module
     * @return void
     */
    public function updated(Module $module): void
    {
        //
    }

    /**
     * Handle the Module "deleted" event.
     *
     * @param Module $module
     * @return void
     */
    public function deleted(Module $module): void
    {
        //
    }

    /**
     * Handle the Module "restored" event.
     *
     * @param Module $module
     * @return void
     */
    public function restored(Module $module): void
    {
        //
    }

    /**
     * Handle the Module "force deleted" event.
     *
     * @param Module $module
     * @return void
     */
    public function forceDeleted(Module $module): void
    {
        //
    }
}
