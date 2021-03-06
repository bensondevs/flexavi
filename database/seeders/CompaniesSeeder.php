<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

use App\Models\User;
use App\Models\Owner;
use App\Models\Company;

class CompaniesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $users = User::where('email', 'like', 'owner%')->get();

        $rawCompanies = [];
        $rawOwners = [];
        foreach ($users as $index => $user) {
            $companyId = generateUuid(); 
            array_push($rawCompanies, [
                'id' => $companyId,
                'company_name' => 'Company ' . ($index + 1),
                'email' => 'company' . ($index + 1) . '@flexavi.com',
                'phone_number' => rand(1000, 9999) * rand(1000, 9999),
                'vat_number' => rand(1000, 9999) * rand(1000, 9999),
                'commerce_chamber_number' => rand(1, 100),
                'company_logo_path' => '/uploads/companies/logos/20210730125714.jpeg',
                'company_website_url' => 'www.randomwebsite.com',
                
                'created_at' => carbon()->now(),
                'updated_at' => carbon()->now(),
            ]);

            array_push($rawOwners, [
                'id' => generateUuid(),
                'user_id' => $user->id,
                'company_id' => $companyId,
                'is_prime_owner' => true,
                'bank_name' => 'FLEXAVIBANK',
                'bic_code' => '9213',
                'bank_account' => '83271221',
                'bank_holder_name' => $user->fullname,
                'created_at' => carbon()->now(),
                'updated_at' => carbon()->now(),
            ]);
        }
        Company::insert($rawCompanies);
        Owner::insert($rawOwners);
    }
}
