<?php

namespace App\Http\Requests\Appointments;

use Illuminate\Foundation\Http\FormRequest;

use App\Models\Appointment;

use App\Traits\InputRequest;

use App\Rules\AmongStrings;

class SaveAppointmentRequest extends FormRequest
{
    use InputRequest;

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
        $user = auth()->user();

        if ($this->isMethod('POST')) {
            return $user->hasCompanyPermission($this->input('company_id'));
        }

        $appointment = $this->getAppointment();
        return $user->hasCompanyPermission(
            $appointment->company_id
        );
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $this->setRules([
            'company_id' => ['required', 'string'],
            'customer_id' => ['required', 'string', 'exists:customers,id'],
            'start' => ['required', 'date'],
            'end' => ['required', 'date'],
            'include_weekend' => ['required', 'boolean'],
            'appointment_type' => [
                'required', 
                'string', 
                new AmongStrings(Appointment::getTypes())
            ],
            'appointment_status' => [
                'required',
                'string',
                new AmongStrings(Appointment::getStatuses())  
            ],
            'note' => ['string'],
        ]);

        return $this->returnRules();
    }
}
