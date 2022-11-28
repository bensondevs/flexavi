<?php

namespace App\Http\Requests\Company\Appointments;

use App\Http\Requests\Company\Appointments\SaveAppointmentRequest as SaveRequest;
use App\Models\Appointment\Appointment;
use App\Traits\CompanyInputRequest;
use Illuminate\Foundation\Http\FormRequest;

class RescheduleAppointmentRequest extends FormRequest
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
        $appointment = $this->getPreviousAppointment();

        return $this->user()
            ->fresh()
            ->can('reschedule-appointment', $appointment);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $saveRequest = new SaveRequest();

        return $saveRequest->rules();
    }

    /**
     * Get reschedule data
     *
     * @return array
     */
    public function rescheduleData()
    {
        $previousAppointment = $this->getPreviousAppointment();
        $data = $this->validated();
        $data['previous_appointment_id'] = $previousAppointment->id;

        return $data;
    }

    /**
     * Prepare for validation
     *
     * @return void
     */
    protected function prepareForValidation()
    {
        $previousAppointment = $this->getPreviousAppointment();
        $this->merge(['customer_id' => $previousAppointment->customer_id]);
    }

    /**
     * Get Previous Appointment based on supplied input
     *
     * @return Appointment
     */
    public function getPreviousAppointment()
    {
        if ($this->appointment) {
            return $this->appointment;
        }
        $id = $this->input('appointment_id') ?: $this->input('id');

        return $this->appointment = Appointment::findOrFail($id);
    }
}
