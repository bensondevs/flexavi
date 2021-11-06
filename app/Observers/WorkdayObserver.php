<?php

namespace App\Observers;

use App\Models\Workday;

class WorkdayObserver
{
    /**
     * Handle the Workday "created" event.
     *
     * @param  \App\Models\Workday  $workday
     * @return void
     */
    public function created(Workday $workday)
    {
        $company = $workday->company;
        $settings = $company->settings;
        //
    }

    /**
     * Handle the Workday "updated" event.
     *
     * @param  \App\Models\Workday  $workday
     * @return void
     */
    public function updated(Workday $workday)
    {
        //
    }

    /**
     * Handle the Workday "deleted" event.
     *
     * @param  \App\Models\Workday  $workday
     * @return void
     */
    public function deleted(Workday $workday)
    {
        //
    }

    /**
     * Handle the Workday "restored" event.
     *
     * @param  \App\Models\Workday  $workday
     * @return void
     */
    public function restored(Workday $workday)
    {
        //
    }

    /**
     * Handle the Workday "force deleted" event.
     *
     * @param  \App\Models\Workday  $workday
     * @return void
     */
    public function forceDeleted(Workday $workday)
    {
        //
    }
}
