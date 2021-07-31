<?php

namespace App\Http\Requests\SubAppointments;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Gate;

use App\Models\Appointment;
use App\Models\SubAppointment;

use App\Enums\SubAppointment\SubAppointmentCancellationVault;

use App\Traits\CompanyInputRequest;

class CancelSubAppointmentRequest extends FormRequest
{
    use CompanyInputRequest;

    private $subAppointment;

    public function getSubAppointment()
    {
        if ($this->subAppointment) return $this->subAppointment;

        $id = $this->input('id') ?: $this->input('sub_appointment_id');
        return $this->subAppointment = SubAppointment::findOrFail($id);
    }

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        $subAppointment = $this->getSubAppointment(); 
        return Gate::allows('cancel-sub-appointment', $subAppointment);
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
                'min:' . SubAppointmentCancellationVault::Roofer, 
                'max:' . SubAppointmentCancellationVault::Customer
            ],
            'cancellation_note' => ['string'],
        ]);

        return $this->returnRules();
    }
}
