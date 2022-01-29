<?php

namespace App\Observers;

use App\Models\{ Workday, Setting };

class WorkdayObserver
{
    /**
     * Handle the Workday "creating" event.
     *
     * @param  \App\Models\Workday  $workday
     * @return void
     */
    public function creating(Workday $workday)
    {
        $workday->id = generateUuid();
    }

    /**
     * Handle the Workday "created" event.
     *
     * @param  \App\Models\Workday  $workday
     * @return void
     */
    public function created(Workday $workday)
    {   
        // Get settings about amount of worklists
        $setting = Setting::findByKey('standard_worklist_quantity');
        $worklistAmount = $setting->getCompanyCastedValue();

        // Generate certain amount of worklist under it
        $workday->generateWorklists($value->casted_value);
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
