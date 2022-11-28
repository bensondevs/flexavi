<?php

namespace Database\Seeders;

use App\Models\Appointment\Appointment;
use Illuminate\Database\Seeder;

class AppointmentSyncsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        foreach (Appointment::get() as $appointment) {
            $appointment->syncWorkdays();
        }
    }
}
