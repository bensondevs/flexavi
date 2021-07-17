<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

use App\Models\Company;
use App\Models\Role;
use App\Models\Employee;

use App\Enums\Employee\EmployeeType;
use App\Enums\Employee\EmploymentStatus;

class EmployeesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $companies = Company::all();
        $users = Role::findByName('employee')->users;

        $rawEmployees = [];
        foreach ($companies as $key => $company) {
            $userEmployees = $users->take(rand(10, 30));
            foreach ($userEmployees as $userEmployee) {
                array_push($rawEmployees, [
                    'id' => generateUuid(),

                    'user_id' => $userEmployee->id,
                    'company_id' => $company->id,

                    'title' => 'Employee Title',
                    'employee_type' => rand(EmployeeType::Administrative, EmployeeType::Roofer),

                    'employment_status' => rand(EmploymentStatus::Active, EmploymentStatus::Fired),
                    
                    'photo_path' => 'uploads/profile_pictures/20210503075156pp.jpeg',

                    'created_at' => carbon()->now(),
                    'updated_at' => carbon()->now(),
                ]);
            }
        }
        Employee::insert($rawEmployees);
    }
}
