<?php

namespace App\Repositories\Appointment;

use App\Models\Appointment\Appointmentable;
use App\Repositories\Base\BaseRepository;
use Illuminate\Database\QueryException;

class AppointmentableRepository extends BaseRepository
{
    /**
     * Repository constructor method
     *
     * @return void
     */
    public function __construct()
    {
        $this->setInitModel(new Appointmentable());
    }

    /**
     * Set the order index
     *
     * @param integer $orderIndex
     * @return Appointmentable|null
     */
    public function setOrderIndex(int $orderIndex)
    {
        try {
            $appointmentable = $this->getModel();
            $appointmentable->order_index = $orderIndex;
            $appointmentable->saveQuietly();
            $this->setSuccess('Successfully to set order index.');
        } catch (QueryException $qe) {
            $error = $qe->getMessage();
            $this->setSuccess('Failed to set order index. ' . $error);
        }

        return $this->getModel();
    }
}
