<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

use App\Models\Employee;
use App\Models\Appointment;
use App\Models\AppointmentWorker;

class AppointmentWorkersSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $appointments = Appointment::with(['company', 'company.employees'])->get();
        $employeeTypes = ['roofers', 'administrative'];

        $rawWorkers = [];
        foreach ($appointments as $appointment) {
        	for ($index = 0; $index < rand(1, 5); $index++) {
        		// Get Random Employee
        		$company = $appointment->company;
                $companyEmployees = $company->employees;
        		if (! $companyEmployees) continue;
        		$employee = $companyEmployees->random();

        		array_push($rawWorkers, [
	        		'id' => generateUuid(),

	        		'company_id' => $appointment->company_id,
	        		'appointment_id' => $appointment->id,
	        		'employee_type' => $employeeTypes[rand(0, 1)],
	        		'employee_id' => $employee->id,

	        		'created_at' => carbon()->now(),
	        		'updated_at' => carbon()->now(),
	        	]);
        	}
        }

        foreach (array_chunk($rawWorkers, 5000) as $_rawWorkers) {
            AppointmentWorker::insert($_rawWorkers);
        }
    }
}
