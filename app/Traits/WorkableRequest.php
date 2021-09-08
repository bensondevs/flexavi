<?php

namespace App\Traits;

use App\Models\Work;

use App\Models\Quotation;
use App\Models\Appointment;
use App\Models\SubAppointment;

trait WorkableRequest 
{
    private $work;

    private $workable;

    private $appointment;
    private $subAppointment;
    private $quotation;

    public function getWork()
    {
        if ($this->work) return $this->work;

        $id = $this->input('work_id');
        return $this->work = Work::findOrFail($id);
    }

    public function getWorkable()
    {
        if ($this->workable) {
            return $this->workable;
        }

        if ($this->has('appointment_id')) {
            return $this->workable = $this->getAppointment();
        }

        if ($this->has('sub_appointment_id')) {
            return $this->workable = $this->getSubAppointment();
        }

        if ($this->has('quotation_id')) {
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

    public function getSubAppointment()
    {
        if ($this->subAppointment) return $this->subAppointment;

        $id = $this->input('sub_appointment_id');
        return $this->subAppointment = SubAppointment::findOrFail($id);
    }

    public function getQuotation()
    {
        if ($this->quotation) return $this->quotation;

        $id = $this->input('quotation_id');
        $quotation = Quotation::findOrFail($id);

        if ($this->work->quotations()->count() > 1) {
            return abort(403, 'Cannot attach work to more than one quotation.');
        }
    }
}