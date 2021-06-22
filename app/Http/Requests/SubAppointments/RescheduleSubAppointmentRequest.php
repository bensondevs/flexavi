<?php

namespace App\Http\Requests\SubAppointments;

use Illuminate\Foundation\Http\FormRequest;

use App\Models\Appointment;
use App\Models\SubAppointment;

use App\Traits\CompanyInputRequest;

class RescheduleSubAppointmentRequest extends FormRequest
{
    use CompanyInputRequest;

    private $subAppointment;

    public function getSubAppointment()
    {
        if ($this->subAppointment) return $this->subAppointment;

        $id = $this->input('id');
        return $this->subAppointment = SubAppointment::findOrFail($id);
    }

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        $user = $this->user();
        $company = $this->getCompany();

        $subAppointment = $this->getSubAppointment();
        $appointment = $subAppointment->appointment;

        return $user->hasCompanyPermission($appointment->company_id, 'reschedule sub appointments');
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $this->setRules([
            'start' => ['required'],
            'end' => ['required'],
        ]);

        return $this->returnRules();
    }
}
