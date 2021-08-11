<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

use App\Repositories\WorkdayRepository;

use App\Jobs\Test\SyncWorkdayAppointments;

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