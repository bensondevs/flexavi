<?php

namespace App\Observers;

use App\Models\ExecuteWork;

class ExecuteWorkObserver
{
    /**
     * Handle the ExecuteWork "created" event.
     *
     * @param  \App\Models\ExecuteWork  $executeWork
     * @return void
     */
    public function created(ExecuteWork $executeWork)
    {
        $work = $executeWork->work;
        $work->process();
    }

    /**
     * Handle the ExecuteWork "updated" event.
     *
     * @param  \App\Models\ExecuteWork  $executeWork
     * @return void
     */
    public function updated(ExecuteWork $executeWork)
    {
        //
    }

    /**
     * Handle the ExecuteWork "deleted" event.
     *
     * @param  \App\Models\ExecuteWork  $executeWork
     * @return void
     */
    public function deleted(ExecuteWork $executeWork)
    {
        //
    }

    /**
     * Handle the ExecuteWork "restored" event.
     *
     * @param  \App\Models\ExecuteWork  $executeWork
     * @return void
     */
    public function restored(ExecuteWork $executeWork)
    {
        //
    }

    /**
     * Handle the ExecuteWork "force deleted" event.
     *
     * @param  \App\Models\ExecuteWork  $executeWork
     * @return void
     */
    public function forceDeleted(ExecuteWork $executeWork)
    {
        //
    }
}
