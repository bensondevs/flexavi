<?php

namespace App\Http\Requests\Workdays\Appointments;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Gate;

use App\Models\Worklist;
use App\Models\Appointment;

class AttachAppointmentRequest extends FormRequest
{
    private $workday;
    private $appointment;

    public function getWorkday()
    {
        if ($this->workday) return $this->workday;

        $id = $this->input('workday_id');
        return $this->workday = Worklist::findOrFail($id);
    }

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
        $workday = $this->getWorkday();
        $appointment = $this->getAppointment();
        return Gate::allows('attach-appointment-workday', [$workday, $appointment]);
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
