<?php

namespace Database\Seeders;

use App\Enums\SubAppointment\{SubAppointmentCancellationVault as Vault, SubAppointmentStatus as Status};
use App\Models\{Appointment\Appointment, Appointment\SubAppointment};
use Illuminate\Database\Seeder;

class SubAppointmentsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $appointments = Appointment::inRandomOrder()->limit(250)->get();

        $rawSubAppointments = [];
        foreach ($appointments as $key => $appointment) {
            for ($index = 0; $index < rand(0, 2); $index++) {
                $rawSubAppointment = [
                    'id' => generateUuid(),
                    'company_id' => $appointment->company_id,
                    'appointment_id' => $appointment->id,
                    'status' => rand(Status::Created, Status::Cancelled),
                    'start' => carbon()->now()->subDays(rand(1, 10)),
                    'end' => carbon()->now()->addDays(rand(1, 10)),
                    'cancellation_cause' => null,
                    'cancellation_vault' => null,
                    'cancellation_note' => null,
                    'created_at' => carbon()->now(),
                    'updated_at' => carbon()->now(),
                    'in_process_at' => null,
                    'processed_at' => null,
                    'cancelled_at' => null,
                ];

                if ($rawSubAppointment['status'] == Status::Cancelled) {
                    $rawSubAppointment['cancellation_cause'] = 'Seeder Cause';
                    $rawSubAppointment['cancellation_vault'] = rand(Vault::Roofer, Vault::Customer);
                    $rawSubAppointment['cancellation_note'] = 'Seeder Note';
                }

                if ($rawSubAppointment['status'] >= Status::InProcess) {
                    $rawSubAppointment['in_process_at'] = now();
                }

                if ($rawSubAppointment['status'] >= Status::Processed) {
                    $rawSubAppointment['processed_at'] = now();
                }

                if ($rawSubAppointment['status'] >= Status::Cancelled) {
                    $rawSubAppointment['cancelled_at'] = now();
                }

                array_push($rawSubAppointments, $rawSubAppointment);
            }
        }
        SubAppointment::insert($rawSubAppointments);
    }
}
