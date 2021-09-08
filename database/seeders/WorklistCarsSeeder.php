<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

use App\Models\Car;
use App\Models\Worklist;
use App\Models\Employee;
use App\Models\WorklistCar;

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
