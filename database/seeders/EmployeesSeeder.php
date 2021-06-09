<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

use App\Models\Company;
use App\Models\Role;
use App\Models\Employee;

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
                    'employee_type' => (['administrative', 'roofers'])[rand(0, 1)],

                    'employment_status' => (['active', 'inactive', 'fired'])[rand(0, 2)],
                    
                    'photo_url' => 'https://dummyimage.com/300/09f/fff.png',

                    'address' => 'Address Test',
                    'house_number' => '11',
                    'house_number_suffix' => 'A',
                    'zipcode' => '117177',
                    'city' => 'Any City',
                    'province' => 'Any Province',

                    'created_at' => carbon()->now(),
                    'updated_at' => carbon()->now(),
                ]);
            }
        }
        Employee::insert($rawEmployees);
    }
}
