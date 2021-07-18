<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

use App\Models\User;
use App\Models\Address;

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
        foreach (User::all() as $user) {
            for ($index = 0; $index < rand(1, 3); $index++) {
                $rawAddresses[] = [
                    'id' => generateUuid(),
                    'user_id' => $user->id,
                    'address' => $user->fullname . ' Address ' . ($index + 1),
                    'house_number' => rand(1, 1000),
                    'house_number_suffix' => 'X',
                    'zipcode' => rand(100000, 999999),
                    'city' => 'Randon City',
                    'province' => 'Random Province',
                    'created_at' => carbon()->now(),
                    'updated_at' => carbon()->now(),
                ];
            }
        }
        Address::insert($rawAddresses);
    }
}
