<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

use App\Models\{
    User,
    Customer,
    Owner,
    Company,
    Address,
    Employee
};

use App\Enums\Address\AddressType;

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

        $rawAddresses = [];
        foreach (Owner::all() as $owner) {
            for ($index = 0; $index < rand(1, 2); $index++) {
                $address = [
                    'id' => generateUuid(),
                    'addressable_type' => Owner::class,
                    'addressable_id' => $owner->id,
                    'address' => $faker->address,
                    'house_number' => $faker->buildingNumber,
                    'house_number_suffix' => rand(0, 1) ?
                        strtoupper($faker->lexify('?')) : null,
                    'zipcode' => $faker->postcode,
                    'city' => $faker->city,
                    'province' => $faker->state,
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
        foreach (array_chunk($rawAddresses, 50) as $ownerChunk) {
            Address::insert($ownerChunk);
        }
        $rawAddresses = array();

        foreach (Company::all() as $company) {
            for ($index = 0; $index < rand(1, 2); $index++) {
                $address = [
                    'id' => generateUuid(),
                    'addressable_type' => Company::class,
                    'addressable_id' => $company->id,
                    'address' => $faker->address,
                    'house_number' => $faker->buildingNumber,
                    'house_number_suffix' => rand(0, 1) ?
                        strtoupper($faker->lexify('?')) : null,
                    'zipcode' => $faker->postcode,
                    'city' => $faker->city,
                    'province' => $faker->state,
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
        foreach (array_chunk($rawAddresses, 500) as $companyChunk) {
            Address::insert($companyChunk);
        }
        $rawAddresses = array();

        foreach (Customer::all() as $customer) {
            for ($index = 0; $index < rand(1, 2); $index++) {
                $address = [
                    'id' => generateUuid(),
                    'addressable_type' => Customer::class,
                    'addressable_id' => $customer->id,
                    'address' => $faker->address,
                    'house_number' => $faker->buildingNumber,
                    'house_number_suffix' => rand(0, 1) ?
                        strtoupper($faker->lexify('?')) : null,
                    'zipcode' => $faker->postcode,
                    'city' => $faker->city,
                    'province' => $faker->state,
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
        foreach (array_chunk($rawAddresses, 500) as $customerChunk) {
            Address::insert($customerChunk);
        }
        $rawAddresses = array();
    
        foreach (Employee::with('user')->get() as $employee) {
            for ($index = 0; $index < rand(1, 2); $index++) {
                $address = [
                    'id' => generateUuid(),
                    'addressable_type' => Employee::class,
                    'addressable_id' => $employee->id,
                    'address' => $faker->address,
                    'house_number' => $faker->buildingNumber,
                    'house_number_suffix' => rand(0, 1) ?
                        strtoupper($faker->lexify('?')) : null,
                    'zipcode' => $faker->postcode,
                    'city' => $faker->city,
                    'province' => $faker->state,
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
        foreach (array_chunk($rawAddresses, 500) as $employeeChunk) {
            Address::insert($employeeChunk);
        }
        $rawAddresses = array();
    }
}
