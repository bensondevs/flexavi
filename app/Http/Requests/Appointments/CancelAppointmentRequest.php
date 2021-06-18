<?php

namespace App\Http\Requests\Appointments;

use Illuminate\Foundation\Http\FormRequest;

use App\Models\Appointment;

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

    protected function prepareForValidation()
    {
        $input = $this->all();

        $reschedule = $this->input('reschedule') ?: false;
        $input['reschedule'] = filter_var($reschedule, FILTER_VALIDATE_BOOLEAN);

        if ($input['reschedule'])
            $input['include_weekend'] = filter_var($this->input('include_weekend'), FILTER_VALIDATE_BOOLEAN);
        $this->replace($input);
    }

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        $user = $this->user();
        $appointment = $this->getAppointment();

        return $user->hasCompanyPermission($appointment->company_id, 'cancel appointments');
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
            'reschedule' => ['boolean'],
        ]);

        if ($this->input('reschedule')) {
            $this->addRule('customer_id', ['required', 'string', 'exists:customers,id']);
            $this->addRule('start', ['required', 'date']);
            $this->addRule('end', ['required', 'date']);
            $this->addRule('include_weekend', ['required', 'boolean']);
            $this->addRule('appointment_type', ['required', 'string', new AmongStrings(Appointment::getTypeValues())]);
            $this->addRule('appointment_status', ['required', 'string', new AmongStrings(Appointment::getStatusValues())]);
            $this->addRule('note', ['string']);
        }

        return $this->returnRules();
    }

    public function cancelData()
    {
        $data = $this->only(['cancellation_cause', 'reschedule']);

        if ($this->input('reschedule')) {
            $data['reschedule_data'] = $this->except([
                'id', 
                'cancellation_cause', 
                'reschedule'
            ]);
        }

        return $data;
    }
}
