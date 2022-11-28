<?php

namespace App\Observers;

use App\Models\Revenue\Revenue;

class RevenueObserver
{
    /**
     * Handle the Revenue "created" event.
     *
     * @param Revenue $revenue
     * @return void
     */
    public function created(Revenue $revenue)
    {
        if ($work = $revenue->work) {
            $work->markRevenueRecorded();
        }
    }

    /**
     * Handle the Revenue "updated" event.
     *
     * @param Revenue $revenue
     * @return void
     */
    public function updated(Revenue $revenue)
    {
        //
    }

    /**
     * Handle the Revenue "deleted" event.
     *
     * @param Revenue $revenue
     * @return void
     */
    public function deleted(Revenue $revenue)
    {
        if ($work = $revenue->work) {
            $work->unmarkRevenueRecorded();
        }
    }

    /**
     * Handle the Revenue "restored" event.
     *
     * @param Revenue $revenue
     * @return void
     */
    public function restored(Revenue $revenue)
    {
        if ($work = $revenue->work) {
            $work->markRevenueRecorded();
        }
    }

    /**
     * Handle the Revenue "force deleted" event.
     *
     * @param Revenue $revenue
     * @return void
     */
    public function forceDeleted(Revenue $revenue)
    {
        if ($work = $revenue->work) {
            $work->unmarkRevenueRecorded();
        }
    }
}
