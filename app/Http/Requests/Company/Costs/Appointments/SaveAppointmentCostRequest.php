<?php

namespace App\Http\Requests\Company\Costs\Appointments;

use App\Models\Appointment\Appointment;
use App\Traits\CompanyInputRequest;
use Illuminate\Foundation\Http\FormRequest;

class SaveAppointmentCostRequest extends FormRequest
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
        $appointment = $this->getAppointment();

        return $this->user()
            ->fresh()
            ->can('create-cost', $appointment);
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
        $id = $this->input('appointment_id');

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
            'company_id' => ['required', 'string'],
            'cost_name' => ['required', 'string'],
            'amount' => ['required', 'numeric'],
            'paid_amount' => ['required', 'numeric'],
        ]);

        return $this->returnRules();
    }

    /**
     * Prepare for validation
     *
     * @return void
     */
    protected function prepareForValidation()
    {
        $this->merge(['company_id' => $this->getCompany()->id]);
        $recordInWorklist = true;
        if ($this->has('record_in_worklist')) {
            $recordInWorklist = strtobool($this->input('record_in_worklist'));
        }
        $this->merge(['record_in_worklist' => $recordInWorklist]);
        $recordInWorkday = true;
        if ($this->has('record_in_workday')) {
            $recordInWorkday = strtobool($this->input('record_in_workday'));
        }
        $this->merge(['record_in_workday' => $recordInWorkday]);
    }
}
