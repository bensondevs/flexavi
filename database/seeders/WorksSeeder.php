<?php

namespace Database\Seeders;

use App\Enums\Work\WorkStatus;
use App\Enums\Work\WorkType;
use App\Models\Appointment\Appointment;
use App\Models\Work\Work;
use App\Models\Work\Workable;
use App\Models\WorkService\WorkService;
use Illuminate\Database\Seeder;

class WorksSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $units = ['m2', 'cm2', 'm', 'l', 'dm3'];

        $rawWorks = [];
        $rawWorkables = [];

        $workServicesId = WorkService::get()->pluck('id')->toArray();

        foreach (Appointment::all() as $index => $appointment) {
            for ($index = 0; $index < rand(1, 3); $index++) {
                $id = generateUuid();
                $unitPrice = rand(10, 200);
                $quantity = rand(1, 1000);
                $subTotal = $unitPrice * $quantity;

                $taxPercentage = 0;
                $taxAmount = 0;
                if ($includeTax = (bool) rand(0, 1)) {
                    $taxPercentage = rand(0, 1) ? 9 : 21;
                    $taxAmount = $subTotal * ($taxPercentage / 100);
                }
                $totalPrice = $subTotal + $taxAmount;

                $status = rand(WorkStatus::Created, WorkStatus::Unfinished);
                $type = rand(WorkType::Additional, WorkType::Planned);

                $executedAt = null;
                if ($status >= WorkStatus::InProcess) {
                    $executedAt = carbon()->now();
                }

                $finishedAt = null;
                $finishedAtAppointmentId = null;
                if ($status >= WorkStatus::Finished) {
                    $finishedAt = carbon()->now();
                    $finishedAtAppointmentId = $appointment->id;
                }

                $unfinishedAt = null;
                if ($status >= WorkStatus::Unfinished) {
                    $unfinishedAt = carbon()->now();
                }

                $rawWorks[] = [
                    'id' => $id,
                    'company_id' => $appointment->company_id,
                    'quantity' => $quantity,
                    'quantity_unit' => $units[rand(0, (count($units) - 1))],
                    'work_service_id' => $workServicesId[rand(0, (count($workServicesId) - 1))],
                    'description' => 'This is seeder appointment work',
                    'unit_price' => $unitPrice,
                    'status' => $status,
                    'type' => $type,
                    'include_tax' => $includeTax,
                    'tax_percentage' => $taxPercentage,
                    'total_price' => $totalPrice,
                    'finished_at_appointment_id' => $finishedAtAppointmentId,
                    'created_at' => carbon()->now(),
                    'updated_at' => carbon()->now(),
                    'executed_at' => $executedAt,
                    'finished_at' => $finishedAt,
                    'unfinished_at' => $unfinishedAt,
                ];


                $rawWorkables[] = [
                    'id' => generateUuid(),
                    'work_id' => $id,
                    'workable_type' => Appointment::class,
                    'workable_id' => $appointment->id,
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
            }
        }

        foreach (array_chunk($rawWorks, 25) as $rawWorksChunk) {
            Work::insert($rawWorksChunk);
        }

        foreach (array_chunk($rawWorkables, 25) as $rawWorkablesChunk) {
            Workable::insert($rawWorkablesChunk);
        }
    }
}
