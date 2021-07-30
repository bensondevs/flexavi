<?php

namespace App\Http\Requests\Appointments;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Gate;

use App\Models\Appointment;

class ExecuteAppointmentRequest extends FormRequest
{
    private $appointment;

    public function getAppointment()
    {
        if ($this->appointment) return $this->appointment;

        $id = $this->input('id') ?: $this->input('appointment_id');
        $this->appointment = Appointment::findOrFail($id);

        return $this->appointment;
    }

    protected function prepareForValidation()
    {
        $appointment = $this->getAppointment();

        if ($appointment->status != 'created') {
            return abort(422, 'This appointment can no longer be executed, because the status is already "' . $appointment->status_description . '"');
        }

        if ($appointment->start < carbon()->now()) {
            return abort(422, 'The appointment is already late, please do cancel and reschedule if needed');
        }
    }

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        $appointment = $this->getAppointment();
        return Gate::allows('execute-appointment', $appointment);
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
