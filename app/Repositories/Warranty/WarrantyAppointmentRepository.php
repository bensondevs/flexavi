<?php

namespace App\Repositories\Warranty;

use App\Models\Warranty\Warranty;
use App\Models\Warranty\WarrantyAppointment;
use App\Repositories\Base\BaseRepository;
use Illuminate\Database\QueryException;

class WarrantyAppointmentRepository extends BaseRepository
{
    /**
     * Repository constructor method
     *
     * @return void
     */
    public function __construct()
    {
        $this->setInitModel(new WarrantyAppointment);
    }


    /**
     * Save warranty work
     *
     * @param  array  $warrantyAppointment
     * @param  \App\Models\Warranty\Warranty  $warranty
     * @return WarrantyAppointment|null
     */
    public function save(array $warrantyAppointment, Warranty $warranty)
    {
        try {
            $data = WarrantyAppointment::create([
                'warranty_id' => $warranty->id,
                'appointment_id' => $warrantyAppointment['appointment_id']
            ]);
            $this->setModel($data->fresh());
            $this->setSuccess('Successfully save warranty.');
        } catch (QueryException $qe) {
            $error = $qe->getMessage();
            $this->setError('Failed to save warranty.', $error);
        }

        return $this->getModel();
    }
}
