<?php

namespace App\Http\Requests\SubAppointments;

use Illuminate\Foundation\Http\FormRequest;

use App\Rules\AmongStrings;

use App\Models\Appointment;
use App\Models\SubAppointment;

use App\Traits\CompanyInputRequest;

class SaveSubAppointmentRequest extends FormRequest
{
    use CompanyInputRequest;

    private $appointment;
    private $subAppointment;

    public function getAppointment()
    {
        if ($this->appointment) return $this->appointment;

        $id = $this->input('appointment_id');
        return $this->appointment = Appointment::findOrFail($id);
    }

    public function getSubAppointment()
    {
        if ($this->subAppointment) return $this->subAppointment;

        $id = $this->input('id');
        return $this->model = $this->subAppointment = SubAppointment::findOrFail($id);
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
        $appointment = $this->getAppointment();

        if (! $user->hasCompanyPermission($appointment->company_id, 'view appointments')) {
            return false;
        }

        if ($this->isMethod('POST')) {
            $user->hasCompanyPermission($appointment->company_id, 'create sub appointments');
        }

        $subAppointment = $this->getSubAppointment();
        if ($subAppointment->appointment_id != $appointment->id) return false;

        return $user->hasCompanyPermission($appointment->company_id, 'edit sub appointments');
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $start = $this->getAppointment()->start;
        $end = $this->getAppointment()->end;

        $this->setRules([
            'appointment_id' => ['required', 'string'],
            'status' => ['required', 'string', new AmongStrings(SubAppointment::getStatusValues())],
            'start' => ['required', 'after_or_equal:' . $start],
            'end' => ['required', 'before_or_equal:' . $end],
            'cancellation_cause' => ['required_if:status,cancelled', 'string'],
            'cancellation_vault' => ['required_if:status,cancelled', 'string', new AmongStrings(SubAppointment::getVaultValues())],
            'cancellation_note' => ['required_if:status,cancelled', 'string'],
        ]);

        return $this->returnRules();
    }
}
