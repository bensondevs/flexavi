<?php

namespace App\Observers;

use App\Models\Work\Workable;

class WorkableObserver
{

    /**
     * Handle the Workable "saving" event.
     *
     * @param Workable $workable
     * @return void
     */
    public function saving(Workable $workable)
    {
        $workable->id = generateUuid();
    }

    /**
     * Handle the Workable "creating" event.
     *
     * @param Workable $workable
     * @return void
     */
    public function creating(Workable $workable)
    {
        $workable->id = generateUuid();
    }

    /**
     * Handle the Workable "created" event.
     *
     * @param Workable $workable
     * @return void
     */
    public function created(Workable $workable)
    {
        //
    }

    /**
     * Handle the Workable "updated" event.
     *
     * @param Workable $workable
     * @return void
     */
    public function updated(Workable $workable)
    {
        //
    }

    /**
     * Handle the Workable "deleted" event.
     *
     * @param Workable $workable
     * @return void
     */
    public function deleted(Workable $workable)
    {
        //
    }

    /**
     * Handle the Workable "restored" event.
     *
     * @param Workable $workable
     * @return void
     */
    public function restored(Workable $workable)
    {
        //
    }

    /**
     * Handle the Workable "force deleted" event.
     *
     * @param Workable $workable
     * @return void
     */
    public function forceDeleted(Workable $workable)
    {
        //
    }
}
