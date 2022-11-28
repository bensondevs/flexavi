<?php

namespace App\Http\Requests\Company\Appointments;

use App\Models\Appointment\Appointment;
use App\Traits\CompanyInputRequest;
use Illuminate\Foundation\Http\FormRequest;

class RestoreAppointmentRequest extends FormRequest
{
    use CompanyInputRequest;

    /**
     * Trashed Appointment object
     *
     * @var Appointment|null
     */
    private $trashedAppointment;

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        $appointment = $this->getTrashedAppointment();

        return $this->user()
            ->fresh()
            ->can('restore-appointment', $appointment);
    }

    /**
     * Get Trashed Appointment based on supplied input
     *
     * @return Appointment
     */
    public function getTrashedAppointment()
    {
        if ($this->trashedAppointment) {
            return $this->trashedAppointment;
        }
        $id = $this->input('id') ?: $this->input('appointment_id');

        return $this->trashedAppointment = Appointment::withTrashed()->findOrFail(
            $id
        );
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
