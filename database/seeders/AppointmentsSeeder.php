<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

use App\Models\Company;
use App\Models\Appointment;

use App\Enums\Appointment\AppointmentType;
use App\Enums\Appointment\AppointmentStatus;

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
        foreach (Company::with('customers')->get() as $company) {
            $worklists = $company->worklists;
            foreach ($company->customers as $customer) {
                for ($index = 0; $index <= rand(1, 3); $index++) {
                	$start = (carbon()->now()->copy())->addDays(rand(-10, 10));
                	$end = ($start->copy())->addDays(rand(0, 7));

                	$type = rand(1, 6);
                	$status = rand(1, 5);

                    $rawAppointment = [
                        'id' => generateUuid(),

                        'company_id' => $company->id,
                        'worklist_id' => null,
                        'customer_id' => $customer->id,

                        'start' => $start,
                        'end' => $end,
                        'include_weekend' => rand(0, 1),

                        'status' => $status,
                        'type' => $type,

                        'note' => 'This is seeder appointment',

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

                    if ($rawAppointment['status'] > AppointmentStatus::InProcess) {
                        $rawAppointment['in_process_at'] = carbon()->now()->addDays(rand(1, 3));
                    }

                    if ($rawAppointment['status'] > AppointmentStatus::Processed) {
                        $rawAppointment['processed_at'] = carbon()->now()->addDays(rand(1, 3));
                    }

                    if ($rawAppointment['status'] > AppointmentStatus::Calculated) {
                        $rawAppointment['calculated_at'] = carbon()->now()->addDays(rand(1, 3));
                    }

                    if ($rawAppointment['status'] == AppointmentStatus::Cancelled) {
                        $rawAppointment['cancellation_cause'] = 'Another cause no one knows';
                        $rawAppointment['cancellation_vault'] = rand(1, 2);
                        $rawAppointment['cancellation_note'] = 'Random cancellation note for appointment';
                        $rawAppointment['cancelled_at'] = carbon()->now()->addDays(rand(3, 5));
                    }

                    if (rand(0, 1)) {
                        $worklist = $worklists->random();
                        $rawAppointment['worklist_id'] = $worklist->id;
                    }

                	$rawAppointments[] = $rawAppointment;
                }
            }

        }

        foreach (array_chunk($rawAppointments, 500) as $chunk) {
            Appointment::insert($chunk);
        }
    }
}
