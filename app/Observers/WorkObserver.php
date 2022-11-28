<?php

namespace App\Observers;

use App\Enums\Work\WorkStatus;
use App\Models\Work\Work;
use App\Repositories\Revenue\RevenueRepository;
use App\Services\Log\LogService;

class WorkObserver
{
    /**
     * Handle the Work "saving" event.
     *
     * @param Work $work
     * @return void
     */
    public function saving(Work $work)
    {
        $work->total_price = $work->countTotalPrice();
    }

    /**
     * Handle the Work "saved" event.
     *
     * @param Work $work
     * @return void
     */
    public function saved(Work $work)
    {
        if ($quotation = $work->quotation) {
            $quotation->countWorksAmount();
            $quotation->saveQuietly();
        }
    }

    /**
     * Handle the Work "creating" event.
     *
     * @param Work $work
     * @return void
     */
    public function creating(Work $work)
    {
        $work->id = generateUuid();
    }

    /**
     * Handle the Work "updated" event.
     *
     * @param Work $Work
     * @return void
     */
    public function updating(Work $work)
    {
        session()->put("props.old.work", $work->getOriginal());

        /*if ($email = $customer->email) {
            if ($customer->isDirty('unique_key')) {
                $mail = new NewUniqueKeyGenerated($customer);

                $sendMailJob = new SendMail($mail, $email);
                $sendMailJob->delay(1);
                dispatch($sendMailJob);
            }
        }*/
    }

    /**
     * Handle the Work "updated" event.
     *
     * @param Work $work
     * @return void
     */
    public function updated(Work $work)
    {
        if ($work->isDirty('quantity') || $work->isDirty('unit_price')) {
            if ($quotation = $work->quotation) {
                $quotation->countWorksAmount();
                $quotation->saveQuietly();
            }
        }

        if ($work->isDirty('status') && $work->status == WorkStatus::Finished) {
            if (!$work->revenue_recorded) {
                $revenueRepository = new RevenueRepository();
                $revenueRepository->recordWork($work);
            }
        }

        if ($user = auth()->user()) {
            if ($work->isDirty('status'))
                LogService::make("work.updates.status")
                    ->with(
                        "old.subject.status_description",
                        WorkStatus::getDescription(session("props.old.work")["status"])
                    )
                    ->by($user)->on($work)->write();
            if ($work->isDirty('quantity'))
                LogService::make("work.updates.quantity")
                    ->with("old.subject.quantity", session("props.old.work")["quantity"])
                    ->by($user)->on($work)->write();
            if ($work->isDirty('quantity_unit'))
                LogService::make("work.updates.quantity_unit")
                    ->with("old.subject.quantity_unit", session("props.old.work")["quantity_unit"])
                    ->by($user)->on($work)->write();
            if ($work->isDirty('description'))
                LogService::make("work.updates.description")
                    ->with("old.subject.description", session("props.old.work")["description"])
                    ->by($user)->on($work)->write();
            if ($work->isDirty('unit_price'))
                LogService::make("work.updates.unit_price")
                    ->with("old.subject.unit_price", session("props.old.work")["unit_price"])
                    ->by($user)->on($work)->write();
            if ($work->isDirty('total_price'))
                LogService::make("work.updates.total_price")
                    ->with("old.subject.total_price", session("props.old.work")["total_price"])
                    ->by($user)->on($work)->write();
        }

        session()->forget("props.old.work");
    }

    /**
     * Handle the Work "executed" event.
     *
     * @param Work $work
     * @return void
     */
    public function executed(Work $work)
    {
        //
    }

    /**
     * Handle the Work "processed" event.
     *
     * @param Work $work
     * @return void
     */
    public function processed(Work $work)
    {
        //
    }

    /**
     * Handle the Work "markedFinsihed" event.
     *
     * @param Work $work
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
     * @param Work $work
     * @return void
     */
    public function markedUnfinished(Work $work)
    {
        //
    }

    /**
     * Handle the Work "restored" event.
     *
     * @param Work $work
     * @return void
     */
    public function restored(Work $work)
    {
        $this->created($work);
        if ($user = auth()->user())
            LogService::make("work.restore")->by($user)->on($work)->write();
    }

    /**
     * Handle the Work "created" event.
     *
     * @param Work $work
     * @return void
     */
    public function created(Work $work)
    {
        if ($quotation = $work->quotation) {
            $quotation->countWorksAmount();
            $quotation->saveQuietly();
        }

        if ($user = auth()->user())
            LogService::make("work.store")->by($user)->on($work)->write();
    }

    /**
     * Handle the Work "force deleted" event.
     *
     * @param Work $work
     * @return void
     */
    public function forceDeleted(Work $work)
    {
        $this->deleted($work);
        if ($user = auth()->user())
            LogService::make("work.force_delete")->by($user)->on($work)->write();
    }

    /**
     * Handle the Work "deleted" event.
     *
     * @param Work $work
     * @return void
     */
    public function deleted(Work $work)
    {
        if ($quotation = $work->quotation) {
            $quotation->amount -= $work->total_price;
            $quotation->saveQuietly();
        }

        if ($user = auth()->user())
            LogService::make("work.delete")->by($user)->on($work)->write();
    }
}
