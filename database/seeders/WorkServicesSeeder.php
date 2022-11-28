<?php

namespace Database\Seeders;

use App\Enums\WorkService\WorkServiceStatus;
use App\Models\Company\Company;
use App\Models\WorkService\WorkService;
use Illuminate\Database\Seeder;

class WorkServicesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run(): void
    {
        $faker = \Faker\Factory::create();

        $availableNames = [
            'Roof Painting',
            'Roof Maintenance',
            'Roof Repairment',
            'Roof Inspection',
            'Change Roof Boarding',
            'Roof Replacement',
            'Roof Destroying'
        ];

        $rawWorkServices = [];
        foreach (Company::all() as $company) {
            for ($i = 0; $i < count($availableNames); $i++) {
                $availableName = $availableNames[$i];

                $createdAt = now()->copy()->subDays(rand(5, 20));
                $active = $i % 2 == 0;

                $rawWorkServices[] = [
                    'id' => generateUuid(),
                    'company_id' => $company->id,
                    'name' => $availableName,
                    'price' => rand(200, 300),
                    'tax_percentage' => rand(0, 10),
                    'description' => $faker->paragraph,
                    'status' => $active ? WorkServiceStatus::Active : WorkServiceStatus::Inactive,
                    'unit' => 'm2',
                    'created_at' => $createdAt->toDateTimeString(),
                    'updated_at' => $createdAt->copy()->addDays(rand(1, 5))->toDateTimeString(),
                    'deleted_at' => $active ?
                        null : $createdAt->copy()->addDays(rand(6, 10))->toDateTimeString(),
                ];
            }
        }

        foreach (array_chunk($rawWorkServices, 100) as $rawWorkServiceChunk) {
            WorkService::insert($rawWorkServiceChunk);
        }
    }
}
