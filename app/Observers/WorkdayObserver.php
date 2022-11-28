<?php

namespace App\Observers;

use App\Enums\Workday\WorkdayStatus;
use App\Models\{Workday\Workday};
use App\Services\Log\LogService;

class WorkdayObserver
{
    /**
     * Handle the Workday "creating" event.
     *
     * @param Workday $workday
     * @return void
     */
    public function creating(Workday $workday)
    {
        $workday->id = generateUuid();
    }

    /**
     * Handle the Workday "created" event.
     *
     * @param Workday $workday
     * @return void
     */
    public function created(Workday $workday)
    {
        $worklistQuantity = 10;

        // Generate certain amount of worklist under it
        $workday->generateWorklists($worklistQuantity);

        if ($user = auth()->user()) {
            LogService::make("workday.store")->by($user)->on($workday)->write();
        }
    }

    /**
     * Handle the Workday "updating" event.
     *
     * @param Workday $workday
     * @return void
     */
    public function updating(Workday $workday)
    {
        session()->put("props.old.workday", $workday->getOriginal());
    }

    /**
     * Handle the Workday "updated" event.
     *
     * @param Workday $workday
     * @return void
     */
    public function updated(Workday $workday)
    {
        if ($user = auth()->user()) {
            if ($workday->isDirty("date")) {
                LogService::make("workday.updates.date")->by($user)->on($workday)->write();
            }
            if ($workday->isDirty("status")) {
                LogService::make("workday.updates.status")
                    ->with(
                        "old.subject.status_description",
                        WorkdayStatus::getDescription(session("props.old.workday")["status"])
                    )
                    ->by($user)->on($workday)->write();
            }
        }

        session()->forget("props.old.workday");
    }

    /**
     * Handle the Workday "deleted" event.
     *
     * @param Workday $workday
     * @return void
     */
    public function deleted(Workday $workday)
    {
        if ($user = auth()->user()) {
            LogService::make("workday.delete")->by($user)->on($workday)->write();
        }
    }

    /**
     * Handle the Workday "restored" event.
     *
     * @param Workday $workday
     * @return void
     */
    public function restored(Workday $workday)
    {
        if ($user = auth()->user()) {
            LogService::make("workday.restore")->by($user)->on($workday)->write();
        }
    }

    /**
     * Handle the Workday "force deleted" event.
     *
     * @param Workday $workday
     * @return void
     */
    public function forceDeleted(Workday $workday)
    {
        if ($user = auth()->user()) {
            LogService::make("workday.force_delete")->by($user)->on($workday)->write();
        }
    }
}
