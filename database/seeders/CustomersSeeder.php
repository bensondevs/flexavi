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
        $companies = $this->company->all()->toArray();
        $totalCompanies = count($companies);
        for ($index = 0; $index < ($totalCompanies * 10); $index++) {
            $company = $companies[rand(0, ($totalCompanies - 1))];
        	$this->customer->save([
                'company_id' => $company['id'],
        
                'fullname' => 'Customer ' . $company['company_name'],
                'salutation' => 'Mr.',
                'address' => 'Customer Address Road',
                'house_number' => rand(1, 100),
                'zipcode' => rand(100, 999) . rand(100, 999),
                'city' => 'Anycity',
                'province' => 'Anyprovince',
                'email' => 'customer' . ($index + 1) . '@' . $company['company_name'] . '.com',
                'phone' => '890123456789',
            ]);
            $this->customer->setModel(new Customer);
        }
    }
}
