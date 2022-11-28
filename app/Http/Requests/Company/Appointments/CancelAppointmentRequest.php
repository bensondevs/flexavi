<?php

namespace App\Http\Requests\Company\Appointments;

use App\Enums\Appointment\AppointmentCancellationVault;
use App\Models\Appointment\Appointment;
use App\Traits\CompanyInputRequest;
use Illuminate\Foundation\Http\FormRequest;

class CancelAppointmentRequest extends FormRequest
{
    use CompanyInputRequest;

    /**
     * Appointment object
     *
     * @var Appointment|null
     */
    private $appointment;

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return $this->user()
            ->fresh()
            ->can('cancel-appointment', $this->getAppointment());
    }

    /**
     * Get Appointment based on supplied input
     *
     * @return Appointment
     */
    public function getAppointment()
    {
        if ($this->appointment) {
            return $this->appointment;
        }
        $id = $this->input('id') ?: $this->input('appointment_id');

        return $this->appointment = Appointment::findOrFail($id);
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
                'max:' . AppointmentCancellationVault::Customer,
            ],
            'cancellation_note' => ['required', 'string'],
        ]);

        return $this->returnRules();
    }

    /**
     * Get cancel data
     *
     * @return array
     */
    public function cancelData()
    {
        return $this->onlyInRules();
    }
}
