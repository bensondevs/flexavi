<?php

namespace App\Http\Requests\Costs\Appointments;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Gate;

use App\Traits\CompanyInputRequest;

use App\Models\Cost;
use App\Models\Appointment;

class SaveAppointmentCostRequest extends FormRequest
{
    use CompanyInputRequest;

    private $appointment;

    public function getAppointment()
    {
        if ($this->appointment) return $this->appointment;

        $id = $this->input('appointment_id');
        return $this->appointment = Appointment::findOrFail($id);
    }

    protected function prepareForValidation()
    {
        $this->merge(['company_id' => $this->getCompany()->id]);

        if ($recordInWorklist = $this->input('record_in_worklist')) {
            $this->merge(['record_in_worklist' => strtobool($recordInWorklist)]);
        }

        if ($recordInWorkday = $this->input('record_in_workday')) {
            $this->merge(['record_in_workday' => strtobool($recordInWorkday)]);
        }
    }

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        $appointment = $this->getAppointment();
        return Gate::allows('create-cost', $appointment);
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
            'cost_name' => ['required', 'string'],
            'amount' => ['required', 'numeric'],
            'paid_amount' => ['required', 'numeric'],
        ]);

        return $this->returnRules();
    }
}
