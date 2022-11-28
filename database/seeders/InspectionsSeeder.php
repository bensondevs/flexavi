<?php

namespace Database\Seeders;

use App\Enums\Appointment\AppointmentType;
use App\Models\{Appointment\Appointment,
    Employee\Employee,
    Inspection\Inspection,
    Inspection\InspectionPicture,
    Inspection\Inspector,
    Work\Work,
    WorkService\WorkService
};
use App\Repositories\Work\WorkRepository;
use Illuminate\Database\Seeder;
use Illuminate\Http\UploadedFile;

class InspectionsSeeder extends Seeder
{

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        $workRepository = new WorkRepository;

        $appointments = Appointment::where('type', AppointmentType::Inspection)->get();
        $rawInspections = [];
        foreach ($appointments as $index => $appointment) {
            array_push($rawInspections, [
                'id' => generateUuid(),
                'company_id' => $appointment->company_id,
                'appointment_id' => $appointment->id,
                'created_at' => carbon()->now(),
                'updated_at' => carbon()->now(),
            ]);
        }

        foreach (array_chunk($rawInspections, 50) as $rawInspection) {
            Inspection::insert($rawInspection);
        }

        $rawPictures = [];
        $rawWorks = [];
        $rawInspectors = [];
        $workIds = [];
        $employees = Employee::roofer()->get();
        foreach (Inspection::all() as $inspection) {
            $amountOfPictures = rand(1, 3);
            for ($i = 0; $i < $amountOfPictures; $i++) {
                array_push($rawPictures, [
                    'id' => generateUuid(),
                    'inspection_id' => $inspection->id,
                    'name' => 'Work on Roof ' . ($i + 1),
                    'amount' => rand(1, 3),
                    'width' => rand(100, 200),
                    'length' => rand(100, 200),
                    'note' => 'This is work on roof ' . ($i + 1),
                    'created_at' => carbon()->now(),
                    'updated_at' => carbon()->now(),
                ]);
                $workService = WorkService::inRandomOrder()->first();

                $workId = generateUuid();
                array_push($rawWorks, [
                    'id' => $workId,
                    'company_id' => $inspection->company_id,
                    'quantity' => rand(1, 3),
                    'work_service_id' => $workService->id,
                    'unit_price' => $workService->price,
                    'created_at' => carbon()->now(),
                    'updated_at' => carbon()->now(),
                ]);
                array_push($workIds, $workId);


                $inspectorId = generateUuid();
                array_push($rawInspectors, [
                    'id' => $inspectorId,
                    'inspection_id' => $inspection->id,
                    'employee_id' => collect($employees->shuffle())
                        ->where("company_id", $inspection->company_id)
                        ->whereNotNull("id")
                        ->first()
                        ->id,
                    'created_at' => carbon()->now(),
                    'updated_at' => carbon()->now(),
                ]);
            }
        }

        foreach (array_chunk($rawPictures, 50) as $rawPicture) {
            InspectionPicture::insert($rawPicture);
        }

        foreach (array_chunk($rawWorks, 50) as $rawWork) {
            Work::insert($rawWork);
        }

        foreach (array_chunk($rawInspectors, 50) as $rawInspector) {
            Inspector::insert($rawInspector);
        }

        foreach (InspectionPicture::get() as $picture) {
            for ($j = 0; $j < rand(1, 2); $j++) {
                $picture->addMedia(
                    UploadedFile::fake()
                        ->image('image.png', 100, 100)
                        ->size(100)
                )->toMediaCollection('inspection_pictures');

                $workService = WorkService::inRandomOrder()->first();
                $workRepository->save([
                    'work_service_id' => $workService->id,
                    'company_id' => $picture->inspection->company_id,
                    'quantity' => $picture->amount,
                    'unit_price' => $workService->price
                ]);
                $workRepository->attachTo($picture);
            }
        }
    }
}
