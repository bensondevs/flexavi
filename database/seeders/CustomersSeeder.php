<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

use App\Models\Company;
use App\Models\Customer;

class CustomersSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $faker = \Faker\Factory::create();

        $rawCustomers = [];
        foreach (Company::all() as $key => $company) {
            for ($index = 0; $index < 50; $index++) {
                array_push($rawCustomers, [
                    'id' => generateUuid(),

                    'company_id' => $company->id,
            
                    'fullname' => $faker->name,
                    'email' => $faker->safeEmail,
                    'phone' => $faker->phoneNumber,

                    'unique_key' => random_string(5),

                    'created_at' => carbon()->now(),
                    'updated_at' => carbon()->now(),
                ]);
            }
        }
        
        foreach (array_chunk($rawCustomers, 100) as $chunk) {
            Customer::insert($chunk);
        }
    }
}
