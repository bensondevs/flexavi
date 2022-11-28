<?php

namespace App\Http\Requests\Company\Appointments;

use App\Models\Appointment\Appointment;
use Illuminate\Foundation\Http\FormRequest;

class CalculateAppointmentRequest extends FormRequest
{
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
        $appointment = $this->getAppointment();

        return $this->user()
            ->fresh()
            ->can('calculate-appointment', $appointment);
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
        $id = $this->input('appointment_id') ?: $this->input('id');

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
