<?php

namespace App\Http\Requests\SubAppointments;

use Illuminate\Foundation\Http\FormRequest;

use App\Rules\AmongStrings;

use App\Models\Appointment;
use App\Models\SubAppointment;

use App\Traits\CompanyInputRequest;

class CancelSubAppointmentRequest extends FormRequest
{
    use CompanyInputRequest;

    private $subAppointment;

    public function getSubAppointment()
    {
        if ($this->subAppointment) return $this->subAppointment;

        $id = $this->input('id');
        return $this->subAppointment = subAppointment::findOrFail($id);
    }

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        $user = $this->user();
        $subAppointment = $this->getSubAppointment();
        $appointment = $subAppointment->appointment;

        return $user->hasCompanyPermission($appointment->company_id, 'cancel sub appointments');
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $this->setRules([
            'cancellation_cause' => ['required'],
            'cancellation_vault' => ['required', new AmongStrings(SubAppointment::getVaultValues())],
            'cancellation_note' => ['required'],
        ]);

        return $this->returnRules();
    }
}
