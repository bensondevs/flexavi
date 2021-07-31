<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

use App\Models\Appointment;
use App\Models\SubAppointment;

use App\Enums\SubAppointment\SubAppointmentStatus;
use App\Enums\SubAppointment\SubAppointmentCancellationVault;

class SubAppointmentsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $appointments = Appointment::inRandomOrder()->limit(750)->get();

        $rawSubAppointments = [];
        foreach ($appointments as $key => $appointment) {
            for ($index = 0; $index < rand(0, 10); $index++) {
                $rawSubAppointment = [
                    'id' => generateUuid(),
                    'company_id' => $appointment->company_id,
                    'appointment_id' => $appointment->id,
                    'status' => rand(SubAppointmentStatus::Created, SubAppointmentStatus::Cancelled),
                    'start' => carbon()->now()->subDays(rand(1, 10)),
                    'end' => carbon()->now()->addDays(rand(1, 10)),
                    'cancellation_cause' => null,
                    'cancellation_vault' => null,
                    'cancellation_note' => null,
                    'created_at' => carbon()->now(),
                    'updated_at' => carbon()->now(),
                ];

                if ($rawSubAppointment['status'] == SubAppointmentStatus::Cancelled) {
                    $rawSubAppointment['cancellation_cause'] = 'Seeder Cause';
                    $rawSubAppointment['cancellation_vault'] = rand(SubAppointmentCancellationVault::Roofer, SubAppointmentCancellationVault::Customer);
                    $rawSubAppointment['cancellation_note'] = 'Seeder Note';
                }

                array_push($rawSubAppointments, $rawSubAppointment);
            }
        }
        SubAppointment::insert($rawSubAppointments);
    }
}