<?php

namespace App\Jobs\Workday;

use App\Jobs\Test\SyncWorkdayAppointments;
use App\Models\{Company\Company};
use App\Services\Workday\GenerateWorkdayService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class GenerateMonthlyWorkdayJob implements ShouldQueue
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
        $activeCompanies = Company::activeSubscription()->get();
        $generateWorkdaysService = new GenerateWorkdayService;
        $generateWorkdaysService->handle(
            $activeCompanies->pluck('id')->toArray(),
            month_start_date_with_sub_months(2),
            month_end_date()
        );

        $syncWorkdayAppointments = new SyncWorkdayAppointments();
        dispatch($syncWorkdayAppointments);
    }
}
