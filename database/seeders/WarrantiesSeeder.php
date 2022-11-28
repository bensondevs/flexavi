<?php

namespace Database\Seeders;

use App\Enums\Appointment\AppointmentType;
use App\Enums\Warranty\WarrantyStatus;
use App\Models\{Appointment\Appointment,
    Warranty\Warranty,
    Warranty\WarrantyAppointment,
    Warranty\WarrantyAppointmentWork};
use Illuminate\Database\Seeder;

class WarrantiesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $this->deleteRelated();

        $appointments = Appointment::where('type', AppointmentType::Warranty)->get();
        $rawWarranties = [];
        $rawWarrantyAppointments = [];
        $rawWarrantyAppointmentWorks = [];
        foreach ($appointments as $appointment) {
            $randomTimestamp =  now()->subDays(rand(1, 30));

            $warrantyId = generateUuid();
            array_push($rawWarranties, [
                'id' => $warrantyId,
                'company_id' => $appointment->company_id,
                'appointment_id' => $appointment->id,
                'status' => WarrantyStatus::Created,
                'created_at' => $randomTimestamp,
                'updated_at' => $randomTimestamp
            ]);
            $relations = $this->linkRelations($warrantyId, $randomTimestamp);
            $rawWarrantyAppointments = $relations['raw_warranty_appointments'];
            $rawWarrantyAppointmentWorks = $relations['raw_warranty_appointment_works'];
        }

        foreach (array_chunk($rawWarranties, 25) as $rawWarrantiesChunk) {
            Warranty::insert($rawWarrantiesChunk);
        }
        foreach (array_chunk($rawWarrantyAppointments, 25) as $rawWarrantyAppointmentsChunk) {
            WarrantyAppointment::insert($rawWarrantyAppointmentsChunk);
        }
        foreach (array_chunk($rawWarrantyAppointmentWorks, 40) as $rawWarrantyAppointmentWorksChunk) {
            WarrantyAppointmentWork::insert($rawWarrantyAppointmentWorksChunk);
        }
    }

    /**
     * delete stuff related to warranty
     *
     * @return void
     */
    private function deleteRelated()
    {
        WarrantyAppointmentWork::whereNotNull('id')->forceDelete();
        WarrantyAppointment::whereNotNull('id')->forceDelete();
        Warranty::whereNotNull('id')->forceDelete();
    }

    /**
     * generate warranty relations
     *
     * @param string $warrantyId
     * @param \Carbon\Carbon $timestamp
     * @return array
     */
    private function linkRelations(string $warrantyId, \Carbon\Carbon $timestamp)
    {
        $rawWarrantyAppointments = [];
        $rawWarrantyAppointmentWorks = [];

        $warrantyAppointments = Appointment::where('type', AppointmentType::ExecuteWork)->take(2)->get();
        foreach ($warrantyAppointments as $warrantyAppointment) {
            $warrantyAppointmentId = generateUuid();
            array_push($rawWarrantyAppointments, [
                'id' => $warrantyAppointmentId,
                'warranty_id' => $warrantyId,
                'appointment_id' => $warrantyAppointment->id,
                'created_at' => $timestamp,
                'updated_at' => $timestamp
            ]);
            foreach ($warrantyAppointment->executeWorks as $executeWork) {
                foreach ($executeWork->photos as $photo) {
                    foreach ($photo->works as $work) {
                        array_push($rawWarrantyAppointmentWorks, [
                            'id' => generateUuid(),
                            'warranty_appointment_id' => $warrantyAppointmentId,
                            'work_warranty_id' => $work->id,
                            'company_paid' => rand(100, 200),
                            'customer_paid' => rand(100, 200),
                            'created_at' => $timestamp,
                            'updated_at' => $timestamp
                        ]);
                    }
                }
            }
        }

        return [
            "raw_warranty_appointments" =>  $rawWarrantyAppointments,
            "raw_warranty_appointment_works" => $rawWarrantyAppointmentWorks
        ];
    }
}
