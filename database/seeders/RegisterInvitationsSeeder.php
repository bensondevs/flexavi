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
            if ($role == 'owner') {
                $owner = Owner::create([
                    'company_id' => $company->id,
                    'is_prime_owner' => false,
                    'bank_name' => 'Invited Bank',
                    'bic_code' => '001',
                    'bank_account' => '1010101010',
                    'bank_holder_name' => 'Invited User',

                    'address' => 'Address Test',
                    'house_number' => '11',
                    'house_number_suffix' => 'A',
                    'zipcode' => '117177',
                    'city' => 'Any City',
                    'province' => 'Any Province',
                ]);

                $attachments = [
                    'role' => $role,
                    'model' => 'App\Models\Owner',
                    'model_id' => $owner->id,
                    'related_column' => 'user_id',
                ];
            } else {
                $employee = Employee::create([
                    'company_id' => $company->id,
                    'title' => 'Invited Employee',
                    'employee_type' => rand(EmployeeType::Administrative, EmployeeType::Roofer),
                    'employment_status' => rand(EmploymentStatus::Active, EmploymentStatus::Fired),

                    'address' => 'Address Test',
                    'house_number' => '11',
                    'house_number_suffix' => 'A',
                    'zipcode' => '117177',
                    'city' => 'Any City',
                    'province' => 'Any Province',
                ]);

                $attachments = [
                    'role' => $role,
                    'model' => 'App\Models\Employee',
                    'model_id' => $employee->id,
                    'related_column' => 'user_id',
                ];
            }

        	array_push($rawInvitations, [
                'registration_code' => 'register' . ($index + 1),
                'invited_email' => 'register' . ($index + 1) . '@flexavi.com',
                'expiry_time' => carbon()->now()->addYears(100),
                'attachments' => json_encode($attachments),
            ]);
        }
        $invitation = RegisterInvitation::insert($rawInvitations);
    }
}
