<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

use App\Models\Appointment;
use App\Models\Cost;

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
        foreach (Appointment::all() as $appointment) {
            for ($index = 0; $index < 1; $index++) {
                $costId = generateUuid();

                $rawCosts[] = [
                    'id' => $costId,
                    'company_id' => $appointment->company_id,
                    'cost_name' => 'Appointment Cost Seeder #' . ($index + 1),
                    'amount' => 1000,
                    'paid_amount' => 200,
                    'created_at' => now(),
                    'updated_at' => now(),
                ];

                $rawCostables[] = [
                    'cost_id' => $costId,
                    'costable_id' => $appointment->id,
                    'costable_type' => get_class($appointment),
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
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
