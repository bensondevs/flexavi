<?php

namespace Database\Seeders;

use App\Models\Company\Company;
use App\Models\Customer\Customer;
use Illuminate\Database\Seeder;

class CustomersSeeder extends Seeder
{
    /**
     * Configure the populated employees per company.
     *
     * @var int
     */
    private int $quantityPerCompany = 20;

    /**
     * Populate customers of the company.
     *
     * @param Company $company
     * @return void
     */
    private function populateCustomers(Company $company): void
    {
        // Create customers for the company
        Customer::factory($this->quantityPerCompany, [
            'company_id' => $company->id,
        ])->for($company)->create();

        // Create soft-deleted customers for the company
        Customer::factory($this->quantityPerCompany, [
            'company_id' => $company->id,
            'deleted_at' => now()->toDateTimeString(),
        ])->for($company)->create();
    }

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run(): void
    {
        $companies = Company::all();
        foreach ($companies as $company) {
            $this->populateCustomers($company);
        }
    }
}
