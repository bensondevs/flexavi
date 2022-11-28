<?php

namespace Database\Seeders;

use App\Enums\Appointment\AppointmentType;
use App\Enums\ExecuteWork\WarrantyTimeType;
use App\Models\{Appointment\Appointment,
    ExecuteWork\ExecuteWork,
    ExecuteWork\ExecuteWorkPhoto,
    ExecuteWork\ExecuteWorkRelatedMaterial,
    ExecuteWork\WorkWarranty,
    Invoice\Invoice,
    Quotation\Quotation,
    WorkService\WorkService};
use Illuminate\Database\Seeder;
use Illuminate\Http\UploadedFile;

class ExecuteWorksSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        $appointments = Appointment::where('type', AppointmentType::ExecuteWork)->get();
        $invoiceIds = Invoice::where('company_id', $appointments[0]->company_id)->get()->pluck('id')->toArray();
        $quotationIds = Quotation::where('company_id', $appointments[0]->company_id)->get()->pluck('id')->toArray();
        $rawExecuteWorks = [];
        $rawRelatedMaterials = [];
        foreach ($appointments as $index => $appointment) {
            $executeWorkId = generateUuid();
            array_push($rawExecuteWorks, [
                'id' => $executeWorkId,
                'company_id' => $appointment->company_id,
                'appointment_id' => $appointment->id,
                'created_at' => carbon()->now(),
                'updated_at' => carbon()->now(),
            ]);

            array_push($rawRelatedMaterials, [
                'id' => generateUuid(),
                'execute_work_id' => $executeWorkId,
                'related_quotation' => true,
                'related_work_contract' => true,
                'related_invoice' => true,
                'invoice_id' => $invoiceIds[rand(0, count($invoiceIds) - 1)],
                'quotation_id' =>  $quotationIds[rand(0, count($quotationIds) - 1)],
                'created_at' => carbon()->now(),
                'updated_at' => carbon()->now(),
            ]);
        }

        foreach (array_chunk($rawExecuteWorks, 50) as $rawExecuteWorksChunk) {
            ExecuteWork::insert($rawExecuteWorksChunk);
        }
        foreach (array_chunk($rawRelatedMaterials, 50) as $rawRelatedMaterialChunk) {
            ExecuteWorkRelatedMaterial::insert($rawRelatedMaterialChunk);
        }

        $rawPhotos = [];
        $rawWorks = [];
        foreach (ExecuteWork::get() as $executeWork) {
            $amountOfPictures = rand(1, 3);
            $relatedMaterial = $executeWork->relatedMaterial ?
                $executeWork->relatedMaterial :
                ExecuteWorkRelatedMaterial::where('execute_work_id', $executeWork->id)->first();

            $relatedMaterial->addMedia(
                UploadedFile::fake()
                    ->image('image.png', 100, 100)
                    ->size(100)
            )->toMediaCollection('quotation_file');

            $relatedMaterial->addMedia(
                UploadedFile::fake()
                    ->image('image.png', 100, 100)
                    ->size(100)
            )->toMediaCollection('invoice_file');

            $relatedMaterial->addMedia(
                UploadedFile::fake()
                    ->image('image.png', 100, 100)
                    ->size(100)
            )->toMediaCollection('work_contract_file');

            for ($i = 0; $i < $amountOfPictures; $i++) {
                $photoId = generateUuid();
                array_push($rawPhotos, [
                    'id' => $photoId,
                    'execute_work_id' => $executeWork->id,
                    'name' => 'Work on Roof ' . ($i + 1),
                    'length' => rand(100, 200),
                    'note' => 'This is work on roof ' . ($i + 1),
                    'created_at' => carbon()->now(),
                    'updated_at' => carbon()->now(),
                ]);
                $workService = WorkService::inRandomOrder()->first();

                $workId = generateUuid();
                array_push($rawWorks, [
                    'id' => $workId,
                    'execute_work_photo_id' => $photoId,
                    'quantity' => rand(1, 3),
                    'quantity_unit' => $workService->unit,
                    'work_service_id' => $workService->id,
                    'unit_price' => $workService->price,
                    'unit_price' => $workService->price * rand(1, 3),
                    'warranty_time_value' => 1,
                    'warranty_time_type' => WarrantyTimeType::getRandomValue(),
                    'created_at' => carbon()->now(),
                    'updated_at' => carbon()->now(),
                ]);
            }
        }

        foreach (array_chunk($rawPhotos, 50) as $rawPhoto) {
            ExecuteWorkPhoto::insert($rawPhoto);
        }

        foreach (array_chunk($rawWorks, 50) as $rawWork) {
            WorkWarranty::insert($rawWork);
        }

        foreach (ExecuteWorkPhoto::get() as $picture) {
            for ($j = 0; $j < rand(1, 2); $j++) {
                $picture->addMedia(
                    UploadedFile::fake()
                        ->image('image.png', 100, 100)
                        ->size(100)
                )->toMediaCollection('execute_work_photos');
            }
        }
    }
}
