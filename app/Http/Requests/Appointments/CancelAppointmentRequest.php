<?php

namespace App\Http\Requests\Appointments;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Gate;

use App\Models\Appointment;

use App\Enums\Appointment\AppointmentCancellationVault;

use App\Traits\CompanyInputRequest;

use App\Rules\AmongStrings;

class CancelAppointmentRequest extends FormRequest
{
    use CompanyInputRequest;

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
        return Gate::allows('cancel-appointment', $this->getAppointment());
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {   
        $this->setRules([
            'cancellation_cause' => ['required', 'string'],
            'cancellation_vault' => [
                'required', 
                'numeric', 
                'min:' . AppointmentCancellationVault::Roofer, 
                'max:' . AppointmentCancellationVault::Customer
            ],
            'cancellation_note' => ['required', 'string'],
        ]);

        return $this->returnRules();
    }

    public function cancelData()
    {
        return $this->onlyInRules();
    }
}
