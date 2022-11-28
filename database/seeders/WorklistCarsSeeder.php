<?php

namespace Database\Seeders;

use App\Models\Car\Car;
use App\Models\Employee\Employee;
use App\Models\Worklist\Worklist;
use App\Models\Worklist\WorklistCar;
use Illuminate\Database\Seeder;

class WorklistCarsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $cars = Car::all();
        $employees = Employee::all();
        $worklists = Worklist::all();

        $rawWorklistCars = [];
        foreach ($worklists as $worklist) {
            $companyCars = $cars->where('company_id', $worklist->company_id);
            $companyEmployees = $employees->where('company_id', $worklist->company_id);
            $rawWorklistCars[] = [
                'id' => generateUuid(),
                'worklist_id' => $worklist->id,
                'car_id' => $companyCars->random()->id,
                'employee_in_charge_id' => $companyEmployees->random()->id,
                'created_at' => now(),
                'updated_at' => now(),
            ];
        }
        WorklistCar::insert($rawWorklistCars);
    }
}
