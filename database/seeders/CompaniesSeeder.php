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
                'company_logo_path' => '/uploads/cars/20210503070400pp.jpeg',
                'company_website_url' => 'www.randomwebsite.com',

                'visiting_address' => json_encode([
                    'street' => 'Custom Road',
                    'house_number' => rand(1, 300),
                    'house_number_suffix' => 'X',
                    'zip_code' => '67312',
                    'city' => 'Random City',
                ]),
                'invoicing_address' => json_encode([
                    'street' => 'Custom Street',
                    'house_number' => rand(1, 250),
                    'house_number_suffix' => 'X',
                    'zip_code' => '65123',
                    'city' => 'Random City',
                ]),
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
        Company::insert($rawCompanies);
        Owner::insert($rawOwners);
    }
}
