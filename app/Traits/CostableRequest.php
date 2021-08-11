<?php

namespace App\Traits;

use App\Models\Workday;
use App\Models\Worklist;
use App\Models\Appointment;

trait CostableRequest 
{
    private $costable;
    
    private $workday;
    private $worklist;
    private $appointment;

    public function getCostable()
    {
        if ($this->costable) {
            return $this->costable;
        }

        if ($this->input('appointment_id')) {
            return $this->getAppointment();
        }

        if ($this->input('worklist_id')) {
            return $this->getWorklist();
        }

        if ($this->input('workday_id')) {
            return $this->getWorkday();
        }

        return abort(404, 'No cost-able type that has been loaded.');
    }

    public function getWorklist()
    {
        if ($this->worklist) return $this->worklist;

        $id = $this->input('worklist_id');
        return $this->worklist = Worklist::findOrFail($id);
    }

    public function getWorkday()
    {
        if ($this->workday) return $this->workday;

        $id = $this->input('workday_id');
        return $this->workday = Workday::findOrFail($id);
    }

    public function getAppointment()
    {
        if ($this->appointment) return $this->appointment;

        $id = $this->input('appointment_id');
        return $this->appointment = Appointment::findOrFail($id);
    }
}