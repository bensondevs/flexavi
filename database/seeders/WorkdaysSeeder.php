<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

use App\Repositories\WorkdayRepository;

class WorkdaysSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $workdayRepository = new WorkdayRepository;
        $workdayRepository->generateWorkdays();
    }
}