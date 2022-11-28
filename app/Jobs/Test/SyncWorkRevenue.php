<?php

namespace App\Jobs\Test;

use App\Enums\Work\WorkStatus;
use App\Models\Work\Work;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

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
        $works = Work::onlyStatus(WorkStatus::Finished)->get();
        foreach ($works as $work) {
            if (isset($work->revenue) && ( $work->revenue_recorded  ==  false)) {
                $work->markRevenueRecorded();
            }
        }
    }
}
