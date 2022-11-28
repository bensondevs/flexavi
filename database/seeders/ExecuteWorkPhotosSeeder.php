<?php

namespace Database\Seeders;

use App\Enums\ExecuteWorkPhoto\PhotoConditionType;
use App\Models\ExecuteWork\ExecuteWork;
use App\Models\ExecuteWork\ExecuteWorkPhoto;
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\{File, Storage};

class ExecuteWorkPhotosSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // create upload directory with the right permission
        $path = Storage::path('execute_works/photos');
        if (!Storage::exists($path)) {
            Storage::makeDirectory($path);
        }

        $executeWorks = ExecuteWork::all();
        $rawPhotos = [];
        foreach ($executeWorks as $executeWork) {
            for ($index = 0; $index < rand(1, 5); $index++) {
                $rawPhotos[] = [
                    'id' => generateUuid(),
                    'execute_work_id' => $executeWork->id,
                    'photo_condition_type' => PhotoConditionType::Before,
                    'photo_path' => ExecuteWorkPhoto::placeholder(),
                    'photo_description' => 'Example of Before Work Photo',
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now(),
                ];
            }

            for ($index = 0; $index < rand(1, 5); $index++) {
                $rawPhotos[] = [
                    'id' => generateUuid(),
                    'execute_work_id' => $executeWork->id,
                    'photo_condition_type' => PhotoConditionType::After,
                    'photo_path' => ExecuteWorkPhoto::placeholder(),
                    'photo_description' => 'Example of After Work Photo',
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now(),
                ];
            }
        }

        foreach (array_chunk($rawPhotos, 25) as $chunk) {
            ExecuteWorkPhoto::insert($chunk);
        }
    }
}
