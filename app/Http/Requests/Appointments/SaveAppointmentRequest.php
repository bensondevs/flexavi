<?php

namespace App\Http\Requests\Appointments;

use Illuminate\Foundation\Http\FormRequest;

use App\Models\Appointment;

class SaveAppointmentRequest extends FormRequest
{
    private $appointment;

    public function getAppointment()
    {
        return $this->appointment = $this->appointment ?:
            Appointment::findOrFail(
                request()->input('id')
            );
    }

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return auth()->user()->hasCompanyPermission(
            $this->appointment->company_id
        );
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $rules = [
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
        ];

        if (request()->input('note'))
            $rules['note'] = ['string', 'alpha_dash'];

        return $rules;
    }
}
