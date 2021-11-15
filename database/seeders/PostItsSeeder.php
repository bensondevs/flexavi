<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

use App\Models\{ Company, Owner, Employee, PostIt };

class PostItsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $rawPostIts = [];
        $companies = Company::with(['owners', 'employees'])->get();
        foreach ($companies as $company) {
            foreach ($company->owners as $owner) {
                for ($i = 0; $i < rand(1, 5); $i++) {
                    array_push($rawPostIts, [
                        'id' => generateUuid(),
                        'company_id' => $company->id,
                        'user_id' => $owner->user_id,
                        'content' => 'Owner post it content #' . ($i + 1),
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]);
                }
            }

            foreach ($company->employees as $employee) {
                for ($i = 0; $i < rand(1, 5); $i++) {
                    array_push($rawPostIts, [
                        'id' => generateUuid(),
                        'company_id' => $company->id,
                        'user_id' => $employee->user_id,
                        'content' => 'Employee post it content #' . ($i + 1),
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]);
                }
            }
        }

        foreach (array_chunk($rawPostIts, 1000) as $chunk) {
            PostIt::insert($chunk);
        }
    }
}