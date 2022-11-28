<?php

namespace App\Http\Requests\Company\Worklists\Appointments;

use App\Models\Appointment\Appointment;
use App\Models\Worklist\Worklist;
use Illuminate\Foundation\Http\FormRequest;

class AttachAppointmentRequest extends FormRequest
{
    /**
     * Worklist object
     *
     * @var Worklist|null
     */
    private $worklist;

    /**
     * Appointment object
     *
     * @var Appointment|null
     */
    private $appointment;

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        $worklist = $this->getWorklist();
        $appointment = $this->getAppointment();

        return $this->user()
            ->fresh()
            ->can('attach-appointment-worklist', [$worklist, $appointment]);
    }

    /**
     * Get Worklist based on supplied input
     *
     * @return Worklist
     */
    public function getWorklist()
    {
        if ($this->worklist) {
            return $this->worklist;
        }
        $id = $this->input('worklist_id');

        return $this->worklist = Worklist::findOrFail($id);
    }

    /**
     * Get Appointment based on supplied input
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
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [];
    }
}
