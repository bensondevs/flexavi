<?php

namespace App\Http\Requests\Appointments;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Gate;

use App\Models\Appointment;

use App\Enums\Appointment\AppointmentType;

use App\Traits\CompanyInputRequest;

class SaveAppointmentRequest extends FormRequest
{
    use CompanyInputRequest;

    private $appointment;

    public function getAppointment()
    {
        if ($this->appointment) return $this->appointment;

        $id = $this->input('id') ?: $this->input('appointment_id');
        return $this->appointment = Appointment::findOrFail($id);
    }

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        if (! $this->isMethod('POST')) {
            $appointment = $this->getAppointment();
            Gate::allows('update-appointment', $appointment);
        }

        return Gate::allows('create-appointment');
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $this->setRules([
            'customer_id' => ['required', 'string', 'exists:customers,id'],
            'start' => ['required', 'date'],
            'end' => ['required', 'date'],
            'include_weekend' => ['boolean'],
            'type' => [
                'required', 
                'numeric', 
                'min:' . AppointmentType::Inspection,
                'max:' . AppointmentType::PaymentReminder,
            ],
            'note' => ['string'],
        ]);

        return $this->returnRules();
    }
}