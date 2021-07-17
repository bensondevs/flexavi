<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

use App\Models\Company;
use App\Models\Customer;

use App\Repositories\CompanyRepository;
use App\Repositories\CustomerRepository;

class CustomersSeeder extends Seeder
{
    private $company;
	private $customer;

	public function __construct(
        CompanyRepository $company,
        CustomerRepository $customer
    )
	{
        $this->company = $company;
		$this->customer = $customer;
	}

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $companies = $this->company->all();

        $rawCustomers = [];
        foreach ($companies as $key => $company) {
            for ($index = 0; $index < 100; $index++) {
                array_push($rawCustomers, [
                    'id' => generateUuid(),

                    'company_id' => $company->id,
            
                    'fullname' => 'Customer ' . ($index + 1) . ' of ' . $company->company_name,
                    'email' => 'customer' . ($index + 1) . '@' . strtolower(str_replace(' ', '', $company->company_name)) . '.com',
                    'phone' => rand(111111111, 999999999),

                    'created_at' => carbon()->now(),
                    'updated_at' => carbon()->now(),
                ]);
            }
        }
        Customer::insert($rawCustomers);
    }
}
