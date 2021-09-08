<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

use App\Models\Work;
use App\Models\Appointment;
use App\Models\ExecuteWork;

use App\Enums\Work\WorkStatus;

class ExecuteWorksSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $inProcessStatus = WorkStatus::InProcess;
        $works = Work::whereHas('appointments')
            ->with('appointments')
            ->onlyStatus($inProcessStatus)
            ->get();

        $rawExecuteWorks = [];
        foreach ($works as $work) {
            for ($index = 0; $index <= rand(1, 2); $index++) {
                $status = rand(1, 2);
                
                $appointment = $work->appointments->first() ?:
                    Appointment::inRandomOrder()->first();
                $rawExecuteWork = [
                    'id' => generateUuid(),
                    'company_id' => $work->company_id,
                    'appointment_id' => $appointment->id,
                    'work_id' => $work->id,
                    'status' => $status,
                    'description' => 'This is work execution',
                    'note' => 'This is seeder execute work ' . ($index + 1),
                    'created_at' => carbon()->now(),
                    'updated_at' => carbon()->now(),
                    'finished_at' => null,
                ];

                if ($status > 1) {
                    $rawExecuteWork['finished_at'] = carbon()->now();
                }

                $rawExecuteWorks[] = $rawExecuteWork;
            }
        }

        foreach (array_chunk($rawExecuteWorks, 5000) as $chunk) {
            ExecuteWork::insert($chunk);
        }
    }
}
