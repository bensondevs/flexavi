<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

use App\Models\ExecuteWork;
use App\Models\ExecuteWorkPhoto;

use App\Enums\ExecuteWorkPhoto\PhotoConditionType;

class ExecuteWorkPhotosSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $executeWorks = ExecuteWork::all();

        $rawPhotos = [];
        foreach ($executeWorks as $executeWork) {
            for ($index = 0; $index < rand(1, 5); $index++) {
                $rawPhotos[] = [
                    'id' => generateUuid(),
                    'execute_work_id' => $executeWork->id,
                    'photo_condition_type' => PhotoConditionType::Before,
                    'photo_path' => storage_path('uploads/works/executes/dummy.jpeg'),
                    'photo_description' => 'Example of Before Work Photo',
                    'created_at' => carbon()->now(),
                    'updated_at' => carbon()->now(),
                ];
            }

            for ($index = 0; $index < rand(1, 5); $index++) {
                $rawPhotos[] = [
                    'id' => generateUuid(),
                    'execute_work_id' => $executeWork->id,
                    'photo_condition_type' => PhotoConditionType::After,
                    'photo_path' => storage_path('uploads/works/executes/dummy.jpeg'),
                    'photo_description' => 'Example of After Work Photo',
                    'created_at' => carbon()->now(),
                    'updated_at' => carbon()->now(),
                ];
            }
        }

        foreach (array_chunk($rawPhotos, 5000) as $chunk) {
            ExecuteWorkPhoto::insert($chunk);
        }
    }
}
