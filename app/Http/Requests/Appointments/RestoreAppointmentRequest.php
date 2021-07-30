<?php

namespace App\Http\Requests\Appointments;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Gate;

use App\Models\Appointment;

use App\Traits\CompanyInputRequest;

class RestoreAppointmentRequest extends FormRequest
{
    use CompanyInputRequest;

    private $trashedAppointment;

    public function getTrashedAppointment()
    {
        if ($this->trashedAppointment) return $this->trashedAppointment;

        $id = $this->input('id') ?: $this->input('appointment_id');
        return $this->trashedAppointment = Appointment::withTrashed()->findOrFail($id);
    }

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        $appointment = $this->getTrashedAppointment();
        return Gate::allows('restore-appointment', $appointment);
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
