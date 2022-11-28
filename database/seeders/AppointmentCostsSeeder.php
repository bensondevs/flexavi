<?php

namespace Database\Seeders;

use App\Models\Appointment\Appointment;
use App\Models\Cost\Cost;
use Illuminate\Database\Seeder;

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

                $createdAt = now()->subDays(rand(0, 30));
                $updatedAt = rand(0, 1) ? $createdAt->addDays(rand(1, 10)) : null;

                $amount = rand(500, 5000);
                $paidAmount = rand(0, $amount);

                array_push($rawCosts, [
                    'id' => $id,
                    'company_id' => $appointment->company_id,
                    'cost_name' => 'Appointment Cost Seeder #' . ($index + 1),
                    'amount' => $amount,
                    'paid_amount' => $paidAmount,
                    'created_at' => $createdAt,
                    'updated_at' => $updatedAt,
                ]);

                array_push($rawCostables, [
                    'cost_id' => $id,
                    'company_id' => $appointment->company_id,
                    'costable_id' => $appointment->id,
                    'costable_type' => get_class($appointment),
                    'created_at' => $createdAt,
                    'updated_at' => $updatedAt,
                ]);

                foreach ($appointment->appointmentables as $appointmentable) {
                    array_push($rawCostables, [
                        'cost_id' => $id,
                        'company_id' => $appointment->company_id,
                        'costable_id' => $appointmentable->appointmentable_id,
                        'costable_type' => $appointmentable->appointmentable_type,
                        'created_at' => $createdAt,
                        'updated_at' => $updatedAt,
                    ]);
                }
            }
        }

        foreach (array_chunk($rawCosts, 25) as $chunk) {
            Cost::insert($chunk);
        }

        foreach (array_chunk($rawCostables, 25) as $chunk) {
            db('costables')->insert($chunk);
        }
    }
}
