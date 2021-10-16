<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

use App\Models\Cost;
use App\Models\Appointment;
use App\Models\Worklist;
use App\Models\Workday;

class AppointmentCostsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $rawCosts = [];
        $rawCostables = [];
        foreach (Appointment::with(['appointmentables'])->get() as $appointment) {
            for ($index = 0; $index < 1; $index++) {
                $id = generateUuid();
                array_push($rawCosts, [
                    'id' => $id,
                    'company_id' => $appointment->company_id,
                    'cost_name' => 'Appointment Cost Seeder #' . ($index + 1),
                    'amount' => 1000,
                    'paid_amount' => 200,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);

                array_push($rawCostables, [
                    'cost_id' => $id,
                    'costable_id' => $appointment->id,
                    'costable_type' => get_class($appointment),
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);

                foreach ($appointment->appointmentables as $appointmentable) {
                    array_push($rawCostables, [
                        'cost_id' => $id,
                        'costable_id' => $appointmentable->appointmentable_id,
                        'costable_type' => $appointmentable->appointmentable_type,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]);
                }
            }
        }

        foreach (array_chunk($rawCosts, 5000) as $chunk) {
            Cost::insert($chunk);
        }

        foreach (array_chunk($rawCostables, 5000) as $chunk) {
            db('costables')->insert($chunk);
        }
    }
}
