<?php

namespace Database\Seeders;

use App\Enums\Worklist\WorklistStatus;
use App\Models\{Employee\Employee, Workday\Workday, Worklist\Worklist};
use Illuminate\Database\Seeder;

class WorklistsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $workdays = Workday::all();
        $rawWorklists = [];
        foreach ($workdays as $workday) {
            $employee = Employee::where('company_id', $workday->company_id)
                ->whereNotNull('user_id')
                ->first();
            if (is_null($employee)) {
                continue;
            }
            for ($i = 0; $i < 2; $i++) {
                $worklist = [
                    'id' => generateUuid(),
                    'company_id' => $workday->company_id,
                    'workday_id' => $workday->id,
                    'worklist_name' => 'Worklist Name ' . ($i + 1),
                    'user_id' => $employee->user_id,
                    'status' => WorklistStatus::getRandomValue(),
                    'created_at' => now(),
                    'updated_at' => now(),
                    'processed_at' => null,
                    'calculated_at' => null,
                ];
                if ($worklist['status'] >= WorklistStatus::Processed) {
                    $worklist['processed_at'] = now();
                }
                if ($worklist['status'] >= WorklistStatus::Calculated) {
                    $worklist['calculated_at'] = now();
                }
                array_push($rawWorklists, $worklist);
            }
        }
        Worklist::insert($rawWorklists);
    }
}
