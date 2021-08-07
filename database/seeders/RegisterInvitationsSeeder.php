<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

use App\Models\Owner;
use App\Models\Company;
use App\Models\Employee;
use App\Models\RegisterInvitation;

use App\Enums\Employee\EmployeeType;
use App\Enums\Employee\EmploymentStatus;

class RegisterInvitationsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $companies = Company::all();

    	$rawInvitations = [];
        for ($index = 0; $index < 100; $index++) {
            $role = rand(0, 1) ? 'owner' : 'employee';
            
            $company = $companies->random();
            
            $attachments = [];
            if ($role == 'employee') {
                $attachments = [
                    'company_id' => $company->id,
                    'title' => 'Employee Title',
                    'employee_type' => rand(1, 2),
                    'employment_status' => 1,
                ];
            }

        	array_push($rawInvitations, [
                'registration_code' => 'register' . ($index + 1),
                'invited_email' => 'register' . ($index + 1) . '@flexavi.com',
                'expiry_time' => carbon()->now()->addYears(100),
                'role' => $role,
                'attachments' => json_encode($attachments),
            ]);
        }
        $invitation = RegisterInvitation::insert($rawInvitations);
    }
}
