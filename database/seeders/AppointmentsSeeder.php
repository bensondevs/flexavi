<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

use App\Models\Company;
use App\Models\Appointment;

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
    	$types = Appointment::TYPES;
    	$statuses = Appointment::STATUSES;

        $rawAppointments = [];
        for ($index = 0; $index < 1000; $index++) {
        	$company = $companies->random();
            $customers = $company->customers;

            if (count($customers) < 1) continue;
        	$customer = $customers->random();

        	$start = (carbon()->now()->copy())->addDays(rand(-10, 10));
        	$end = ($start->copy())->addDays(rand(0, 7));

        	$type = $types[rand(0, count($types) - 1)];
        	$status = $statuses[rand(0, count($statuses) - 1)];

        	array_push($rawAppointments, [
        		'id' => generateUuid(),

        		'company_id' => $company->id,
        		'customer_id' => $customer->id,

        		'start' => $start,
        		'end' => $end,
        		'include_weekend' => rand(0, 1),

        		'appointment_type' => $type['value'],
        		'appointment_status' => $status['value'],

        		'note' => 'This is seeder appointment',
        	]);
        }
        Appointment::insert($rawAppointments);
    }
}
