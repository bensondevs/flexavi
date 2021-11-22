<?php

namespace App\Http\Requests\Worklists\Appointments;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Gate;
use App\Models\{ Worklist, Appointment };

class MoveAppointmentRequest extends FormRequest
{
    /**
     * Target worklist model container
     * 
     * @var \App\Models\Worklist
     */
    private $worklist;

    /**
     * Moved appointment model container
     * 
     * @var \App\Models\Appointment
     */
    private $appointment

    /**
     * Get worklist from the request value
     * 
     * @return \App\Models\Worklist
     */
    public function getWorklist()
    {
        if ($this->worklist) return $this->worklist;

        $id = $this->input('worklist_id');
        return $this->worklist = Worklist::findOrFail($id);
    }

    /**
     * Get appointment from the request value
     * 
     * @return \App\Models\Appointment
     */
    public function getAppointment()
    {
        if ($this->appointment) return $this->appointment;

        $id = $this->input('appointment_id');
        return $this->appointment = Appointment::findOrFail($id);
    }

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        $worklist = $this->getWorklist();
        $appointment = $this->getAppointment();
        return Gate::allows('move-appointment-worklist', [$worklist, $appointment]);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            //
        ];
    }
}
