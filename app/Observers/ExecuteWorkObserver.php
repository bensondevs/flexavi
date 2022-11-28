<?php

namespace App\Observers;

use App\Models\ExecuteWork\ExecuteWork;
use App\Models\ExecuteWork\ExecuteWorkRelatedMaterial;

class ExecuteWorkObserver
{
    /**
     * Handle the ExecuteWork "creating" event.
     *
     * @param ExecuteWork $executeWork
     * @return void
     */
    public function creating(ExecuteWork $executeWork)
    {
        $executeWork->id = generateUuid();
    }

    /**
     * Handle the ExecuteWork "created" event.
     *
     * @param ExecuteWork $executeWork
     * @return void
     */
    public function created(ExecuteWork $executeWork)
    {
        ExecuteWorkRelatedMaterial::create([
            'execute_work_id' => $executeWork->id
        ]);
    }

    /**
     * Handle the ExecuteWork "updated" event.
     *
     * @param ExecuteWork $executeWork
     * @return void
     */
    public function updated(ExecuteWork $executeWork)
    {
        //
    }

    /**
     * Handle the ExecuteWork "deleted" event.
     *
     * @param ExecuteWork $executeWork
     * @return void
     */
    public function deleted(ExecuteWork $executeWork)
    {
        //
    }

    /**
     * Handle the ExecuteWork "restored" event.
     *
     * @param ExecuteWork $executeWork
     * @return void
     */
    public function restored(ExecuteWork $executeWork)
    {
        //
    }

    /**
     * Handle the ExecuteWork "force deleted" event.
     *
     * @param ExecuteWork $executeWork
     * @return void
     */
    public function forceDeleted(ExecuteWork $executeWork)
    {
        //
    }
}
