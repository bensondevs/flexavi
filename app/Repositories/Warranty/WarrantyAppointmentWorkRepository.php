<?php

namespace App\Repositories\Warranty;

use App\Models\ExecuteWork\WorkWarranty;
use App\Models\Warranty\WarrantyAppointment;
use App\Models\Warranty\WarrantyAppointmentWork;
use App\Repositories\Base\BaseRepository;
use Illuminate\Database\QueryException;

class WarrantyAppointmentWorkRepository extends BaseRepository
{
    /**
     * Repository constructor method
     *
     * @return void
     */
    public function __construct()
    {
        $this->setInitModel(new WarrantyAppointmentWork);
    }

    /**
     * Save warranty work
     *
     * @param  array  $work
     * @param  \App\Models\Warranty\WarrantyAppointment  $warrantyAppointment
     * @return WarrantyAppointmentWork|null
     */
    public function save(array $work, WarrantyAppointment $warrantyAppointment)
    {
        try {
            $data = WarrantyAppointmentWork::create([
                'warranty_appointment_id' => $warrantyAppointment->id,
                'work_warranty_id' => $work['work_warranty_id'],
                'company_paid' => $work['company_paid'],
                'customer_paid' => (WorkWarranty::find($work['work_warranty_id'])->total_price - $work['company_paid']),
            ]);
            $this->setModel($data->fresh());
            $this->setSuccess('Successfully save warranty work.');
        } catch (QueryException $qe) {
            $error = $qe->getMessage();
            $this->setError('Failed to save warranty work.', $error);
        }

        return $this->getModel();
    }
}
