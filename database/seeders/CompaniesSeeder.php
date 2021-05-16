<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

use App\Models\Role;
use App\Models\User;
use App\Models\Owner;
use App\Models\Company;

use App\Repositories\CompanyRepository;
use App\Repositories\CompanyOwnerRepository;

class CompaniesSeeder extends Seeder
{
    private $owner;
	private $company;

	public function __construct(
        CompanyRepository $company,
        CompanyOwnerRepository $owner
    )
	{
        $this->owner = $owner;
		$this->company = $company;
	}

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $ownerRole = Role::findByName('owner');
    	$owners = $ownerRole->users()->get();

    	foreach ($owners as $index => $userOwner) {
            $owner = [
                'user_id' => $userOwner->id,
                'bank_name' => 'FLEXAVIBANK',
                'bic_code' => 'DUMMYBICCODE',
                'bank_account' => '098912361352',
                'bank_holder_name' => 'Bank Holder',
            ];
            $this->owner->save($owner);

            $company = [
                'owner_id' => $this->owner->getModel()->id,

                'company_name' => 'Company ' . ($index + 1),

                'visiting_address' => [
                    'street' => ($index + 1) . ' Random Street',
                    'house_number' => rand(1, 100),
                    'house_number_suffix' => 'Random Suffix',
                    'zip_code' => rand(10000, 99999),
                    'city' => 'Semarang',
                ],
                'invoicing_address' => [
                    'street' => ($index + 1) . ' Random Street',
                    'house_number' => rand(1, 100),
                    'house_number_suffix' => 'Random Suffix',
                    'zip_code' => rand(10000, 99999),
                    'city' => 'Semarang',
                ],

                'email' => 'email' . ($index + 1) . '@company.com',
                'phone_number' => '1010101010101',
                'vat_number' => 'FLEXAVITESTVAT007',
                'commerce_chamber_number' => 'CN001',
                'company_logo_url' => 'https://dummyimage.com/300/09f/fff.png',
                'company_website_url' => 'web.company' . ($index + 1) . '.com',
            ];
    		$this->company->save($company);

            $this->owner->setModel(new Owner);
            $this->company->setModel(new Company);
        }
    }
}
