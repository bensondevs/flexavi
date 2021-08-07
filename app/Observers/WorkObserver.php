<?php

namespace App\Observers;

use App\Models\Work;

class WorkObserver
{
    /**
     * Handle the Work "created" event.
     *
     * @param  \App\Models\Work  $work
     * @return void
     */
    public function created(Work $work)
    {
        //
    }

    /**
     * Handle the Work "updated" event.
     *
     * @param  \App\Models\Work  $work
     * @return void
     */
    public function updated(Work $work)
    {
        //
    }

    /**
     * Handle the Work "executed" event.
     *
     * @param  \App\Models\Work  $work
     * @return void
     */
    public function executed(Work $work)
    {
        //
    }

    /**
     * Handle the Work "processed" event.
     *
     * @param  \App\Models\Work  $work
     * @return void
     */
    public function processed(Work $work)
    {
        //
    }

    /**
     * Handle the Work "markedFinsihed" event.
     *
     * @param  \App\Models\Work  $work
     * @return void
     */
    public function markedFinished(Work $work)
    {
        //
    }

    /**
     * Handle the Work "markedUnfinished" event.
     *
     * @param  \App\Models\Work  $work
     * @return void
     */
    public function markedUnfinished(Work $work)
    {
        //
    }

    /**
     * Handle the Work "deleted" event.
     *
     * @param  \App\Models\Work  $work
     * @return void
     */
    public function deleted(Work $work)
    {
        //
    }

    /**
     * Handle the Work "restored" event.
     *
     * @param  \App\Models\Work  $work
     * @return void
     */
    public function restored(Work $work)
    {
        //
    }

    /**
     * Handle the Work "force deleted" event.
     *
     * @param  \App\Models\Work  $work
     * @return void
     */
    public function forceDeleted(Work $work)
    {
        //
    }
}
