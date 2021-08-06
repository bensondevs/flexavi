<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

use App\Models\Workday;
use App\Models\Worklist;

use App\Enums\Worklist\WorklistStatus;

class WorklistsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $rawWorklists = [];
        foreach (Workday::all() as $workday) {
            for ($index = 0; $index < rand(0, 5); $index++) {
                $worklist = [
                    'id' => generateUuid(),
                    'company_id' => $workday->company_id,
                    'workday_id' => $workday->id,
                    'worklist_name' => 'Worklist Name ' . ($index + 1),
                    'status' => rand(1, 3),
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

                $rawWorklists[] = $worklist;
            }
        }

        Worklist::insert($rawWorklists);
    }
}
