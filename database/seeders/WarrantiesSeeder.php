<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

use App\Models\Work;
use App\Models\Warranty;

use App\Enums\Work\WorkStatus;
use App\Enums\Warranty\WarrantyStatus;

class WarrantiesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $rawWarranties = [];
        foreach (Work::onlyStatus(WorkStatus::Finished)->get() as $work) {
            $appointmentId = $work->finished_at_appointment_id;
            $amount = rand(0, 1000);

            $rawWarranties[] = [
                'id' => generateUuid(),
                'company_id' => $work->company_id,
                'appointment_id' => $appointmentId,
                'work_id' => $work->id,
                'status' => rand(WarrantyStatus::Created, WarrantyStatus::Unfixed),
                'problem_description' => 'Problem seeder',
                'fixing_description' => 'Fixing seeder',
                'internal_note' => 'Note for internal seeder',
                'customer_note' => 'Note from customer seeder',
                'amount' => $amount,
                'paid_amount' => rand(0, $amount),
                'created_at' => now(),
                'updated_at' => now(),
            ];
        }

        foreach (array_chunk($rawWarranties, 500) as $rawWarrantiesChunk) {
            Warranty::insert($rawWarrantiesChunk);
        }

    }
}
