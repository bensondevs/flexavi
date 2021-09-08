<?php

namespace App\Jobs\Test;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

use App\Models\Work;

use App\Enums\Work\WorkStatus;

class SyncWorkRevenue implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        foreach (Work::onlyStatus(WorkStatus::Finished)->get() as $work) {
            if ($work->revenue && (! $work->revenue_recorded)) {
                $work->markRevenueRecorded();
            }
        }
    }
}
