<?php

namespace App\Jobs\Workday;

use App\Jobs\Test\SyncWorkdayAppointments;
use App\Models\Company\Company;
use App\Services\Workday\GenerateWorkdayService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class GenerateWorkdayForPreviouslyInactiveCompanyJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Found company model container
     *
     * @var Company|null
     */
    private $company;

    /**
     * Create a new job instance.
     *
     * @param \App\Models\Company\Company $company
     * @return void
     */
    public function __construct(Company $company)
    {
        $this->company = $company;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $company = $this->company;
        $generateWorkdaysService = new GenerateWorkdayService;
        $generateWorkdaysService->handle(
            [
                $company->id
            ],
            month_start_date_with_sub_months(2),
            month_end_date()
        );


        $syncWorkdayAppointments = new SyncWorkdayAppointments();
        dispatch($syncWorkdayAppointments);
    }
}
