<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

use App\Models\Car;
use App\Models\Company;

class CarsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $rawCars = [];
        foreach (Company::all() as $key => $company) {
        	for ($index = 0; $index < rand(5, 20); $index++) {
        		$rawCars[] = [
        			'id' => generateUuid(),
        			'company_id' => $company->id,
        			'car_image_path' => 'uploads/cars/9812378123.jpeg',

        			'brand' => 'Fleet Brand',
        			'model' => 'Fleet Model',
        			'year' => rand(2010, 2021),
        			'car_name' => 'Seeder Car Name',
        			'car_license' => 'SEEDER_LICENSE_DATA',
        			'insured' => rand(0, 1),
        		];
        	}
        }

        foreach (array_chunk($rawCars, 500) as $chunk) {
        	Car::insert($chunk);
        }
    }
}
