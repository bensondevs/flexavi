<?php

namespace App\Http\Requests\Appointments;

use Illuminate\Foundation\Http\FormRequest;

use App\Models\Appointment;

class DeleteAppointmentRequest extends FormRequest
{
    private $appointment;

    public function getAppointment()
    {
        return $this->appointment = $this->appointment ?:
            Appointment::withTrashed()->findOrFail($this->input('id'));
    }

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return gate()->allows('delete-appointment', $this->getAppointment());
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'force' => ['boolean'],
        ];
    }
}
