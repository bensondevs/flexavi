<?php

namespace Database\Seeders;

use App\Enums\Address\AddressType;
use App\Models\{Address\Address, Company\Company, Customer\Customer, Employee\Employee, Owner\Owner};
use Illuminate\Database\Seeder;

class AddressesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $faker = \Faker\Factory::create();

        $addresses = json_decode(
            file_get_contents(
                resource_path('json/addresses/us-address.json')
            ),
            true
        );

        $rawAddresses = array();
        foreach (Owner::all() as $owner) {
            for ($index = 0; $index < rand(1, 2); $index++) {

                $randomAddress = $addresses[array_rand($addresses, 1)];
                $address = [
                    'id' => generateUuid(),
                    'addressable_type' => Owner::class,
                    'addressable_id' => $owner->id,
                    'address' => $randomAddress['address1'],
                    'house_number' => $faker->buildingNumber,
                    'house_number_suffix' => rand(0, 1) ?
                        strtoupper($faker->lexify('?')) : null,
                    'zipcode' => $randomAddress['postalCode'],
                    'city' => $randomAddress['city'],
                    'province' => $randomAddress['state'],
                    'latitude' => $randomAddress['coordinates']['lat'],
                    'longitude' => $randomAddress['coordinates']['lng'],
                    'created_at' => carbon()->now(),
                    'updated_at' => carbon()->now(),
                ];
                $address['address_type'] = rand(AddressType::VisitingAddress, AddressType::Other);
                $address['other_address_type_description'] = null;
                if ($address['address_type'] == AddressType::Other) {
                    $address['other_address_type_description'] = 'Custom Name Addess Type';
                }
                array_push($rawAddresses, $address);
            }
        }
        foreach (array_chunk($rawAddresses, 25) as $ownerChunk) {
            Address::insert($ownerChunk);
        }



        $rawAddresses = array();
        foreach (Company::all() as $company) {
            for ($index = 0; $index < rand(1, 2); $index++) {
                $randomAddress = $addresses[array_rand($addresses, 1)];
                $randomAddress2 = $addresses[array_rand($addresses, 1)];

                $visitingAddress = [
                    'id' => generateUuid(),
                    'addressable_type' => Company::class,
                    'addressable_id' => $company->id,
                    'address' => $randomAddress['address1'],
                    'house_number' => $faker->buildingNumber,
                    'house_number_suffix' => rand(0, 1) ?
                        strtoupper($faker->lexify('?')) : null,
                    'zipcode' => $randomAddress['postalCode'],
                    'city' => $randomAddress['city'],
                    'province' => $randomAddress['state'],
                    'latitude' => $randomAddress['coordinates']['lat'],
                    'longitude' => $randomAddress['coordinates']['lng'],
                    'address_type' => AddressType::VisitingAddress,
                    'created_at' => carbon()->now(),
                    'updated_at' => carbon()->now(),
                ];

                $invoicingAddress = [
                    'id' => generateUuid(),
                    'addressable_type' => Company::class,
                    'addressable_id' => $company->id,
                    'address' => $randomAddress2['address1'],
                    'house_number' => $faker->buildingNumber,
                    'house_number_suffix' => rand(0, 1) ?
                        strtoupper($faker->lexify('?')) : null,
                    'zipcode' => $randomAddress2['postalCode'],
                    'city' => $randomAddress2['city'],
                    'province' => $randomAddress2['state'],
                    'latitude' => $randomAddress2['coordinates']['lat'],
                    'longitude' => $randomAddress2['coordinates']['lng'],
                    'address_type' => AddressType::InvoicingAddress,
                    'created_at' => carbon()->now(),
                    'updated_at' => carbon()->now(),
                ];

                array_push($rawAddresses, $visitingAddress);
                array_push($rawAddresses, $invoicingAddress);
            }
        }
        foreach (array_chunk($rawAddresses, 25) as $companyChunk) {
            Address::insert($companyChunk);
        }



        $rawAddresses = array();
        foreach (Customer::all() as $customer) {
            for ($index = 0; $index < rand(1, 2); $index++) {
                $randomAddress = $addresses[array_rand($addresses, 1)];

                $address = [
                    'id' => generateUuid(),
                    'addressable_type' => Customer::class,
                    'addressable_id' => $customer->id,
                    'address' => $randomAddress['address1'],
                    'house_number' => $faker->buildingNumber,
                    'house_number_suffix' => rand(0, 1) ?
                        strtoupper($faker->lexify('?')) : null,
                    'zipcode' => $randomAddress['postalCode'],
                    'city' => $randomAddress['city'],
                    'province' => $randomAddress['state'],
                    'latitude' => $randomAddress['coordinates']['lat'],
                    'longitude' => $randomAddress['coordinates']['lng'],
                    'created_at' => carbon()->now(),
                    'updated_at' => carbon()->now(),
                ];
                $address['address_type'] = rand(AddressType::VisitingAddress, AddressType::Other);
                $address['other_address_type_description'] = null;
                if ($address['address_type'] == AddressType::Other) {
                    $address['other_address_type_description'] = 'Custom Name Addess Type';
                }

                array_push($rawAddresses, $address);
            }
        }
        foreach (array_chunk($rawAddresses, 25) as $customerChunk) {
            Address::insert($customerChunk);
        }


        $rawAddresses = array();
        foreach (Employee::with('user')->get() as $employee) {
            for ($index = 0; $index < rand(1, 2); $index++) {
                $randomAddress = $addresses[array_rand($addresses, 1)];

                $address = [
                    'id' => generateUuid(),
                    'addressable_type' => Employee::class,
                    'addressable_id' => $employee->id,
                    'address' => $randomAddress['address1'],
                    'house_number' => $faker->buildingNumber,
                    'house_number_suffix' => rand(0, 1) ?
                        strtoupper($faker->lexify('?')) : null,
                    'zipcode' => $randomAddress['postalCode'],
                    'city' => $randomAddress['city'],
                    'province' => $randomAddress['state'],
                    'latitude' => $randomAddress['coordinates']['lat'],
                    'longitude' => $randomAddress['coordinates']['lng'],
                    'created_at' => carbon()->now(),
                    'updated_at' => carbon()->now(),
                ];
                $address['address_type'] = rand(AddressType::VisitingAddress, AddressType::Other);
                $address['other_address_type_description'] = null;
                if ($address['address_type'] == AddressType::Other) {
                    $address['other_address_type_description'] = 'Custom Name Addess Type';
                }

                array_push($rawAddresses, $address);
            }
        }
        foreach (array_chunk($rawAddresses, 25) as $employeeChunk) {
            Address::insert($employeeChunk);
        }
        $rawAddresses = array();
    }
}
