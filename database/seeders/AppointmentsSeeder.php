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
    	$companies = Company::with('customers')->get();

        $rawAppointments = [];
        for ($index = 0; $index < 5000; $index++) {
        	$company = $companies->random();
            $customers = $company->customers;

            if (count($customers) < 1) continue;
        	$customer = $customers->random();

        	$start = (carbon()->now()->copy())->addDays(rand(-10, 10));
        	$end = ($start->copy())->addDays(rand(0, 7));

        	$type = rand(1, 6);
        	$status = rand(1, 5);

        	array_push($rawAppointments, [
        		'id' => generateUuid(),

        		'company_id' => $company->id,
        		'customer_id' => $customer->id,

        		'start' => $start,
        		'end' => $end,
        		'include_weekend' => rand(0, 1),

        		'type' => $type,
        		'status' => $status,

        		'note' => 'This is seeder appointment',
        	]);
        }

        $chuckedRawAppontments = array_chunk($rawAppointments, 500);
        foreach ($chuckedRawAppontments as $_rawAppointments) {
            Appointment::insert($_rawAppointments);
        }
    }
}
