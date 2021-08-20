<?php

namespace App\Traits;

use App\Models\Quotation;
use App\Models\Appointment;

trait WorkableRequest 
{
    private $workable;

    private $appointment;
    private $quotation;

    public function getWorkable()
    {
        if ($this->workable) {
            return $this->workable;
        }

        if ($this->input('appointment_id')) {
            return $this->workable = $this->getAppointment();
        }

        if ($this->input('quotation_id')) {
            return $this->workable = $this->getQuotation();
        }

        return abort(404, 'No work attachable type has been loaded.');
    }

    public function getAppointment()
    {
        if ($this->appointment) return $this->appointment;

        $id = $this->input('appointment_id');
        return $this->appointment = Appointment::findOrFail($id);
    }

    public function getQuotation()
    {
        if ($this->quotation) return $this->quotation;

        $id = $this->input('quotation_id');
        return $this->quotation = Quotation::findOrFail($id);
    }
}