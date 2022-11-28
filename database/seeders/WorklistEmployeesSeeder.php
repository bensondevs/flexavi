<?php

namespace Database\Seeders;

use App\Models\{Employee\Employee, Worklist\Worklist, Worklist\WorklistEmployee};
use Illuminate\Database\Seeder;

class WorklistEmployeesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $rawWorklistEmployees = [];
        foreach (Worklist::inRandomOrder()->limit(50)->get() as $worklist) {
            for ($index = 0; $index < rand(0, 5); $index++) {
                $employee = Employee::inRandomOrder()
                    ->where('company_id', $worklist->company_id)->first();
                if ($employee->count()) {
                    array_push($rawWorklistEmployees, [
                        'id' => generateUuid(),
                        'worklist_id' => $worklist->id,
                        'user_id' => $employee->user_id,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]);
                }
            }
        }
        foreach (array_chunk($rawWorklistEmployees, 50) as $rawWorklistEmployeesChunk) {
            WorklistEmployee::insert($rawWorklistEmployeesChunk);
        }
    }
}
