<?php

namespace Database\Seeders;

use App\Enums\Address\AddressType;
use App\Models\Address\Address;
use App\Models\Customer\Customer;
use Illuminate\Database\Seeder;

class CustomerAddressesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run(): void
    {

        $faker = \Faker\Factory::create();

        $addresses = json_decode(
            file_get_contents(
                resource_path('json/addresses/us-address.json')
            ),
            true
        );


        $rawAddresses = [];

        $customers = Customer::with('address')->get();
        foreach ($customers as $index => $customer) {
            if (!$customer->address) {
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

                $rawAddresses[] = $address;
            }
        }

        foreach (array_chunk($rawAddresses, 70) as $customerChunk) {
            Address::insert($customerChunk);
        }
    }
}
