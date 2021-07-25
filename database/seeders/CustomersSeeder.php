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
        $companies = Company::all();

        $rawCustomers = [];
        foreach ($companies as $key => $company) {
            for ($index = 0; $index < 1000; $index++) {
                array_push($rawCustomers, [
                    'id' => generateUuid(),

                    'company_id' => $company->id,
            
                    'fullname' => 'Customer ' . ($index + 1) . ' of ' . $company->company_name,
                    'email' => 'customer' . ($index + 1) . '@' . strtolower(str_replace(' ', '', $company->company_name)) . '.com',
                    'phone' => random_phone(13),

                    'unique_key' => random_string(5),

                    'address' => 'Customer ' . ($index + 1) . ' address',
                    'house_number' => rand(1, 1000),
                    'house_number_suffix' => 'X',
                    'zipcode' => rand(100000, 999999),
                    'city' => 'Randon City',
                    'province' => 'Random Province',

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
