<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

use App\Models\User;
use App\Models\Customer;
use App\Models\Owner;
use App\Models\Company;
use App\Models\Address;
use App\Models\Employee;

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
        $rawAddresses = [];
        foreach (Owner::whereHas('user')->with('user')->get() as $owner) {
            for ($index = 0; $index < rand(1, 3); $index++) {
                $address = [
                    'id' => generateUuid(),
                    'addressable_type' => Owner::class,
                    'addressable_id' => $owner->id,
                    'address' => 'Owner Address ',
                    'house_number' => rand(1, 1000),
                    'house_number_suffix' => 'X',
                    'zipcode' => rand(100000, 999999),
                    'city' => 'Randon City',
                    'province' => 'Random Province',
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
        Address::insert($rawAddresses);
        $rawAddresses = array();

        foreach (Company::all() as $company) {
            for ($index = 0; $index < rand(1, 3); $index++) {
                $address = [
                    'id' => generateUuid(),
                    'addressable_type' => Company::class,
                    'addressable_id' => $company->id,
                    'address' => $company->company_name . ' Address ',
                    'house_number' => rand(1, 1000),
                    'house_number_suffix' => 'X',
                    'zipcode' => rand(100000, 999999),
                    'city' => 'Randon City',
                    'province' => 'Random Province',
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
        Address::insert($rawAddresses);
        $rawAddresses = array();

        foreach (Customer::all() as $customer) {
            for ($index = 0; $index < rand(1, 3); $index++) {
                $address = [
                    'id' => generateUuid(),
                    'addressable_type' => Customer::class,
                    'addressable_id' => $customer->id,
                    'address' => $customer->fullname . ' Address ',
                    'house_number' => rand(1, 1000),
                    'house_number_suffix' => 'X',
                    'zipcode' => rand(100000, 999999),
                    'city' => 'Randon City',
                    'province' => 'Random Province',
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
        Address::insert($rawAddresses);
        $rawAddresses = array();
    
        foreach (Employee::with('user')->get() as $employee) {
            for ($index = 0; $index < rand(1, 3); $index++) {
                $address = [
                    'id' => generateUuid(),
                    'addressable_type' => Employee::class,
                    'addressable_id' => $employee->id,
                    'address' => 'Employee Address',
                    'house_number' => rand(1, 1000),
                    'house_number_suffix' => 'X',
                    'zipcode' => rand(100000, 999999),
                    'city' => 'Randon City',
                    'province' => 'Random Province',
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
        Address::insert($rawAddresses);
        $rawAddresses = array();
    }
}
