<?php

namespace App\Traits;

use App\Models\Appointment\Appointment;
use App\Models\Appointment\SubAppointment;
use App\Models\Quotation\Quotation;
use App\Models\Work\Work;
use Illuminate\Support\Collection;

trait WorkableRequest
{
    /**
     * The WorkableRequest attributes
     *
     * @var mixed
     */
    private $work;
    private $works;
    private $workable;
    private $appointment;
    private $subAppointment;
    private $quotation;

    /**
     * Get Work based on supplied input
     *
     * @return Work
     */
    public function getWork()
    {
        if ($this->work) {
            return $this->work;
        }
        $id = $this->input('work_id');

        return $this->work = Work::findOrFail($id);
    }

    /**
     * Get Work collection based on supplied input
     *
     * @return Collection
     */
    public function getWorks()
    {
        if ($this->works) {
            return $this->works;
        }
        $id = $this->input('work_ids');

        return $this->works = Work::whereIn('id', $id)->get();
    }

    /**
     * Get the workable
     *
     * @return mixed
     */
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

    /**
     * Get the appointment
     *
     * @return Appointment
     */
    public function getAppointment()
    {
        if ($this->appointment) {
            return $this->appointment;
        }
        $id = $this->input('appointment_id');

        return $this->appointment = Appointment::findOrFail($id);
    }

    /**
     * Get the SubAppointment
     *
     * @return SubAppointment
     */
    public function getSubAppointment()
    {
        if ($this->subAppointment) {
            return $this->subAppointment;
        }
        $id = $this->input('sub_appointment_id');

        return $this->subAppointment = SubAppointment::findOrFail($id);
    }

    /**
     * Get the Quotation
     *
     * @return Quotation
     */
    public function getQuotation()
    {
        if ($this->quotation) {
            return $this->quotation;
        }
        $id = $this->input('quotation_id');
        if ($this->has('work_ids') && count($this->input('work_ids')) > 0) {
            foreach ($this->getWorks() as $work) {
                if ($work->quotations()->count() > 1) {
                    return abort(
                        403,
                        'Cannot attach work to more than one quotation.'
                    );
                }
            }
        }

        return $this->quotation = Quotation::findOrFail($id);
    }
}
