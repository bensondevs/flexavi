<?php

namespace App\Http\Requests\Appointments;

use Illuminate\Foundation\Http\FormRequest;

use App\Models\Appointment;

use App\Traits\CompanyInputRequest;

class RestoreAppointmentRequest extends FormRequest
{
    use CompanyInputRequest;

    private $trashedAppointment;

    public function getTrashedAppointment()
    {
        return $this->trashedAppointment = $this->trashedAppointment ?:
            Appointment::withTrashed()->findOrFail($this->input('id'));
    }

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        $appointment = $this->getTrashedAppointment();
        return $this->checkCompanyPermission('restore appointments', $appointment);
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
