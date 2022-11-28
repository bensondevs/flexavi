<?php

namespace Database\Seeders;

use App\Enums\Appointment\{AppointmentStatus};
use App\Models\{Appointment\Appointment, Appointment\Appointmentable, Company\Company, Employee\Employee, Owner\Owner};
use Illuminate\Database\Seeder;

class AppointmentsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $rawAppointments = [];
        $rawAppointmentables = [];

        $employees = Employee::all();
        $owners = Owner::all();

        foreach (Company::with(['customers', 'worklists'])->get() as $company) {
            foreach ($company->customers as $customer) {
                if ($company->worklists->isEmpty()) {
                    continue;
                }
                $worklist = $company->worklists->random();
                for ($index = 0; $index < rand(3, 5); $index++) {



                    $id = generateUuid();
                    $start = (carbon()->now()->copy())->addDays(rand(-10, 10));
                    $end = ($start->copy())->addDays(rand(3, 7));
                    $type = rand(1, 6);
                    $status = rand(1, 5);
                    $rawAppointment = [
                        'id' => $id,
                        'company_id' => $company->id,
                        'customer_id' => $customer->id,
                        'start' => $start,
                        'end' => $end,
                        'include_weekend' => rand(0, 1),
                        'status' => $status,
                        'type' => $type,
                        'note' => 'This is seeder appointment',
                        'description' => 'This description seeder appointment',

                        'cancellation_cause' => null,
                        'cancellation_vault' => null,
                        'cancellation_note' => null,
                        'created_at' => carbon()->now(),
                        'updated_at' => carbon()->now(),
                        'in_process_at' => null,
                        'processed_at' => null,
                        'calculated_at' => null,
                        'cancelled_at' => null,
                    ];
                    if (
                        $rawAppointment['status'] > AppointmentStatus::InProcess
                    ) {
                        $rawAppointment['in_process_at'] = carbon()
                            ->now()
                            ->addDays(rand(1, 3));
                    }
                    if (
                        $rawAppointment['status'] > AppointmentStatus::Processed
                    ) {
                        $rawAppointment['processed_at'] = carbon()
                            ->now()
                            ->addDays(rand(1, 3));
                    }
                    if (
                        $rawAppointment['status'] >
                        AppointmentStatus::Calculated
                    ) {
                        $rawAppointment['calculated_at'] = carbon()
                            ->now()
                            ->addDays(rand(1, 3));
                    }
                    if (
                        $rawAppointment['status'] ==
                        AppointmentStatus::Cancelled
                    ) {
                        $rawAppointment['cancellation_cause'] =
                            'Another cause no one knows';
                        $rawAppointment['cancellation_vault'] = rand(1, 2);
                        $rawAppointment['cancellation_note'] =
                            'Random cancellation note for appointment';
                        $rawAppointment['cancelled_at'] = carbon()
                            ->now()
                            ->addDays(rand(3, 5));
                    }
                    $rawAppointments[] = $rawAppointment;
                    $rawAppointmentables[] = [
                        'id' => generateUuid(),
                        'company_id' => $worklist->company_id,
                        'appointment_id' => $id,
                        'appointmentable_type' => get_class($worklist),
                        'appointmentable_id' => $worklist->id,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ];
                }
            }
        }
        foreach (array_chunk($rawAppointments, 25) as $rawAppointmentsChunk) {
            Appointment::insert($rawAppointmentsChunk);
        }
        foreach (array_chunk($rawAppointmentables, 25)
            as $rawAppointmentablesChunk) {
            Appointmentable::insert($rawAppointmentablesChunk);
        }
    }
}
