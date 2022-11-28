<?php

namespace Database\Seeders;

use App\Models\Customer\Customer;
use App\Models\Customer\CustomerNote;
use Faker\Factory;
use Illuminate\Database\Seeder;

class CustomerNotesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run(): void
    {
        $faker = Factory::create();
        $rawNotes = [];
        $createdAt = now();
        $updatedAt = now();
        foreach (Customer::all() as $customer) {
            for ($i = 1; $i < rand(10, 15); $i++) {
                $rawNotes[] = [
                    'id' => generateUuid(),
                    'customer_id' => $customer->id,
                    'note' => $faker->sentence,
                    'created_at' => $createdAt,
                    'updated_at' => $updatedAt,
                    'deleted_at' => null
                ];
            }
            for ($i = 1; $i < rand(3, 5); $i++) {
                $rawNotes[] = [
                    'id' => generateUuid(),
                    'customer_id' => $customer->id,
                    'note' => $faker->sentence,
                    'created_at' => $createdAt,
                    'updated_at' => $updatedAt,
                    'deleted_at' => $updatedAt
                ];
            }
        }

        foreach (array_chunk($rawNotes, 50) as $rawNotesChunk) {
            CustomerNote::insert($rawNotesChunk);
        }
    }
}
