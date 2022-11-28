<?php

namespace Database\Seeders;

use App\Models\{Appointment\Appointment, Appointment\AppointmentEmployee, Employee\Employee, Owner\Owner};
use Illuminate\Database\Seeder;

class AppointmentEmployeesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $appointments = Appointment::all();
        $employees = Employee::all();
        $owners = Owner::all();
        $users = [$owners, $employees];

        $rawAppointmentEmployees = [];
        foreach ($appointments as  $appointment) {
            $companyEmployee = $users[array_rand($users, 1)]->where('company_id', $appointment->company_id);
            if ($employee = $companyEmployee->random()) {
                $rawAppointmentEmployees[] = [
                    'id' => generateUuid(),
                    'appointment_id' => $appointment->id,
                    'user_id' => $employee->user_id,
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
            }
        }

        foreach (array_chunk($rawAppointmentEmployees, 50) as $chunk) {
            AppointmentEmployee::insert($chunk);
        }
    }
}
