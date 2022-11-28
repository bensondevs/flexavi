<?php

namespace Database\Seeders;

use App\Jobs\Test\SyncWorkdayAppointments;
use App\Repositories\Workday\WorkdayRepository;
use Illuminate\Database\Seeder;

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

        $syncWorkdayAppointments = new SyncWorkdayAppointments();
        $syncWorkdayAppointments->delay(1);
        dispatch($syncWorkdayAppointments);
    }
}
