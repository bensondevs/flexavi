<?php

namespace App\Observers;

use App\Models\Work;

use App\Enums\Work\WorkStatus;

use App\Repositories\RevenueRepository;

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
        if ($quotation = $work->quotation) {
            $quotation->countWorksAmount();
            $quotation->save();
        }
    }

    /**
     * Handle the Work "updated" event.
     *
     * @param  \App\Models\Work  $work
     * @return void
     */
    public function updated(Work $work)
    {
        if ($work->isDirty('quantity') || $work->isDirty('unit_price')) {
            if ($work->quotation) {
                $quotation->countWorksAmount();
                $quotation->save();
            }
        }

        if ($work->isDirty('status') && $work->status == WorkStatus::Finished) {
            if (! $work->revenue_recorded) {
                $revenueRepository = new RevenueRepository();
                $revenueRepository->recordWork($work);
            }
        }
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
        $revenueRepository = new RevenueRepository();
        $revenueRepository->recordWork($work);
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
        if ($quotation = $work->quotation) {
            $quotation->amount -= $work->total_price;
            $quotation->save();
        }
    }

    /**
     * Handle the Work "restored" event.
     *
     * @param  \App\Models\Work  $work
     * @return void
     */
    public function restored(Work $work)
    {
        $this->created($work);
    }

    /**
     * Handle the Work "force deleted" event.
     *
     * @param  \App\Models\Work  $work
     * @return void
     */
    public function forceDeleted(Work $work)
    {
        $this->deleted($work);
    }
}
