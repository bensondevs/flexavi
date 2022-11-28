<?php

namespace App\Jobs\Workday;

use App\Enums\{Workday\WorkdayStatus, Worklist\WorklistStatus};
use App\Jobs\Test\SyncWorkdayAppointments;
use App\Models\{Company\Company, Workday\Workday, Worklist\Worklist};
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class CreateDailyWorkdayJob implements ShouldQueue
{
    use Dispatchable;
    use InteractsWithQueue;
    use Queueable;
    use SerializesModels;

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
        $companies = Company::get();
        $date = now()->format('Y-m-d');
        $rawWorkdays = [];
        $rawWorklists = [];
        foreach ($companies as $company) {
            $worklistQuantity = 10;

            if (!Workday::where('company_id', $company->id)->where('date', $date)->exists()) {
                $workdayId = generateUuid();
                array_push($rawWorkdays, [
                    'id' => $workdayId,
                    'company_id' => $company->id,
                    'status' => WorkdayStatus::Prepared,
                    'date' => $date,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);

                for ($index = 0; $index < $worklistQuantity; $index++) {
                    array_push($rawWorklists, [
                        'id' => generateUuid(),
                        'company_id' => $company->id,
                        'workday_id' => $workdayId,
                        'worklist_name' => 'Worklist Name ' . ($index + 1),
                        'status' => WorklistStatus::Prepared,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]);
                }
            }
        }

        foreach (array_chunk($rawWorkdays, 50) as $workdayChunk) {
            Workday::insert($workdayChunk);
        }

        foreach (array_chunk($rawWorklists, 50) as $worklistChunk) {
            Worklist::insert($worklistChunk);
        }

        $syncWorkdayAppointments = new SyncWorkdayAppointments();
        $syncWorkdayAppointments->delay(1);
        dispatch($syncWorkdayAppointments);
    }
}
