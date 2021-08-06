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
        foreach (Appointment::all() as $appointment) {
            for ($index = 0; $index < 5; $index++) {
                $rawCosts[] = [
                    'id' => generateUuid(),
                    'company_id' => $appointment->company_id,
                    'costable_id' => $appointment->id,
                    'costable_type' => get_class($appointment),
                    'cost_name' => 'Appointment Cost Seeder #' . ($index + 1),
                    'amount' => 1000,
                    'paid_amount' => 200,
                    'receipt_path' => 'uploads/appointments/costs/receipts/9812378123.jpeg',
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
            }
        }

        foreach (array_chunk($rawCosts, 5000) as $chunk) {
            Cost::insert($chunk);
        }
    }
}
