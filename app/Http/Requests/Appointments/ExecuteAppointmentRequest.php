<?php

namespace App\Http\Requests\Appointments;

use Illuminate\Foundation\Http\FormRequest;

class ExecuteAppointmentRequest extends FormRequest
{
    private $appointment;

    public function getAppointment()
    {
        if ($this->appointment) return $this->appointment;

        $id = $this->input('id');
        return $this->appointment = Appointment::findOrFail($id);
    }

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        $user = $this->user();
        $appointment = $this->getAppointment();
        return $user->hasCompanyPermission($appointment->company_id, 'execute appointments');
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
