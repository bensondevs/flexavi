<?php

namespace App\Http\Requests\Appointments;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Gate;

use App\Models\Appointment;

use App\Traits\CompanyInputRequest;

use App\Rules\AmongStrings;

class SaveAppointmentRequest extends FormRequest
{
    use CompanyInputRequest;

    private $appointment;

    public function getAppointment()
    {
        return $this->appointment = $this->model = $this->appointment ?:
            Appointment::findOrFail($this->input('id'));
    }

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        $appointment = $this->getAppointment();

        return $this->isMethod('POST') ? 
            Gate::allows('create-appointment', $appointment) :
            Gate::allows('update-appointment', $appointment);
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
            'include_weekend' => ['required', 'boolean'],
            'appointment_type' => [
                'required', 
                'string', 
                new AmongStrings(Appointment::getTypeValues())
            ],
            'appointment_status' => [
                'required',
                'string',
                new AmongStrings(Appointment::getStatusValues())  
            ],
            'note' => ['string'],
        ]);

        return $this->returnRules();
    }
}
