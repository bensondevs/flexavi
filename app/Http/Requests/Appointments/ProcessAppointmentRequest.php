<?php

namespace App\Http\Requests\Appointments;

use Illuminate\Support\Facades\Gate;
use Illuminate\Foundation\Http\FormRequest;

use App\Models\Appointment;

class ProcessAppointmentRequest extends FormRequest
{
    private $appointment;

    public function getAppointment()
    {
        if ($this->appointment) return $this->appointment;

        $id = $this->input('id') ?: $this->input('appointment_id');
        return Appointment::findOrFail($id);
    }

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        $appointment = $this->getAppointment();

        return Gate::allows('process-appointment', $appointment);
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
