<?php

namespace Database\Seeders;

use App\Models\Appointment\Appointment;
use App\Models\Appointment\RelatedAppointment;
use Illuminate\Database\Seeder;

class RelatedAppointmentsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $rawRelateds = [];
        $appointments = Appointment::all();
        foreach ($appointments as $appointment) {
            $related = rand(1, 3);
            for ($index = 0; $index < $related; $index++) {
                $rawRelateds[] = [
                    'id' => generateUuid(),
                    'appointment_id' => $appointment->id,
                    'related_appointment_id' => $appointments->where('company_id', $appointment->company_id)->whereNotIn('id', [$appointment->id])->random()->first()->id,
                    'created_at' => now(),
                    'updated_at' => now()
                ];
            }
        }

        foreach (array_chunk($rawRelateds, 50) as $rawRelatedChunk) {
            RelatedAppointment::insert($rawRelatedChunk);
        }
    }
}
