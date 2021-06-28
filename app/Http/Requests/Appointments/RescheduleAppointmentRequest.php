<?php

namespace App\Http\Requests\Appointments;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Gate;

use App\Models\Appointment;

use App\Http\Requests\Appointments\SaveAppointmentRequest as SaveRequest;

class RescheduleAppointmentRequest extends FormRequest
{
    use CompanyInputRequest;

    private $appointment;

    public function getPreviousAppointment()
    {
        if ($this->appointment) return $this->appointment;

        $id = $this->input('previous_appointment_id');
        return $this->appointment = Appointment::findOrFail($id);
    }

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        $appointment = $this->getPreviousAppointment();

        return Gate::allows('reschedule-appointment', $appointment);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $saveRequest = new SaveRequest();
        return $saveRequest->rules();
    }

    public function rescheduleData()
    {
        $data = $this->ruleWithCompany();
        $data['previous_appointment_id'] = $this->getPreviousAppointment()->id;

        return $data;
    }
}
