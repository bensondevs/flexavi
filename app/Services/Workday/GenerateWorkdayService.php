<?php

namespace App\Services\Workday;

use App\Enums\Workday\WorkdayStatus;
use App\Enums\Worklist\WorklistStatus;
use App\Models\{Company\Company, Workday\Workday, Worklist\Worklist};
use Carbon\{Carbon, CarbonPeriod};

class GenerateWorkdayService
{
    /**
     *
     *
     * @param array $companiesId
     * @param mixed $start
     * @param mixed $end
     */
    public function handle(
        array $companiesId,
        mixed $start,
        mixed $end
    ): void
    {
        $companies = Company::whereIn('id', $companiesId)->get();
        $rawWorkdays = [];
        $rawWorklists = [];


        foreach ($companies as $company) {

            $worklistQuantity = 10;

            foreach (CarbonPeriod::create(
                $start,
                $end
            ) as $date) {

                if (!Workday::where('company_id', $company->id)->where('date', Carbon::parse($date)->format('Y-m-d'))->exists()) {
                    $workdayId = generateUuid();
                    $rawWorkdays[] = [
                        'id' => $workdayId,
                        'company_id' => $company->id,
                        'status' => WorkdayStatus::Prepared,
                        'date' => $date,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ];

                    for ($index = 0; $index < $worklistQuantity; $index++) {
                        $rawWorklists[] = [
                            'id' => generateUuid(),
                            'company_id' => $company->id,
                            'workday_id' => $workdayId,
                            'worklist_name' => 'Worklist Name ' . ($index + 1),
                            'status' => WorklistStatus::Prepared,
                            'created_at' => now(),
                            'updated_at' => now(),
                        ];
                    }
                }
            }
        }

        foreach (array_chunk($rawWorkdays, 50) as $workdayChunk) {
            Workday::insert($workdayChunk);
        }

        foreach (array_chunk($rawWorklists, 50) as $worklistChunk) {
            Worklist::insert($worklistChunk);
        }
    }
}
