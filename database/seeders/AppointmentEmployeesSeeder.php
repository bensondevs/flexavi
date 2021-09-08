<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

use App\Models\Employee;
use App\Models\Appointment;
use App\Models\AppointmentEmployee;

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

        $rawAppointmentEmployees = [];
        foreach ($appointments as $appointment) {
            $companyEmployee = $employees->where('company_id', $appointment->company_id);

            if ($employee = $companyEmployee->random()) {
                $rawAppointmentEmployees[] = [
                    'id' => generateUuid(),
                    'appointment_id' => $appointment->id,
                    'employee_id' => $employee->id,
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
            }
        }

        AppointmentEmployee::insert($rawAppointmentEmployees);
    }
}
