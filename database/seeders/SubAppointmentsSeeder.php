<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

use App\Models\Appointment;
use App\Models\SubAppointment;

class SubAppointmentsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $appointments = Appointment::inRandomOrder()
            ->limit(rand(500, 1000))
            ->get();
        $statuses = SubAppointment::getStatusValues();
        $vaults = SubAppointment::getVaultValues();


        $rawSubAppointments = [];
        foreach ($appointments as $key => $appointment) {
            for ($index = 0; $index < rand(0, 10); $index++) {
                $rawSubAppointment = [
                    'id' => generateUuid(),
                    'appointment_id' => $appointment->id,
                    'status' => $statuses[rand(0, (count($statuses) - 1))],
                    'start' => carbon()->now()->subDays(rand(1, 10)),
                    'end' => carbon()->now()->addDays(rand(1, 10)),
                    'cancellation_cause' => null,
                    'cancellation_vault' => null,
                    'cancellation_note' => null,
                    'created_at' => carbon()->now(),
                    'updated_at' => carbon()->now(),
                ];

                if ($rawSubAppointment['status'] == 'cancelled') {
                    $rawSubAppointment['cancellation_cause'] = 'Seeder Cause';
                    $rawSubAppointment['cancellation_vault'] = $vaults[rand(0, (count($vaults) - 1))];
                    $rawSubAppointment['cancellation_note'] = 'Seeder Note';
                }

                array_push($rawSubAppointments, $rawSubAppointment);
            }
        }
        SubAppointment::insert($rawSubAppointments);
    }
}
