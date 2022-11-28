<?php

namespace App\Http\Requests\Company\SubAppointments;

use App\Models\Appointment\Appointment;
use App\Models\Appointment\SubAppointment;
use App\Traits\CompanyInputRequest;
use Illuminate\Foundation\Http\FormRequest;

class SaveSubAppointmentRequest extends FormRequest
{
    use CompanyInputRequest;

    /**
     * Appointment object
     *
     * @var Appointment|null
     */
    private $appointment;

    /**
     * SubAppointment object
     *
     * @var SubAppointment|null
     */
    private $subAppointment;

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        $user = $this->user()->fresh();
        if (!$this->isMethod('POST')) {
            $subAppointment = $this->getSubAppointment();
            return $user->can('edit-sub-appointment', $subAppointment);
        }
        $appointment = $this->getAppointment();

        return $user->can('create-sub-appointment', $appointment);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $start = $this->getAppointment()->start;
        $end = $this->getAppointment()->end;
        $this->setRules([
            'appointment_id' => ['required', 'string'],
            'company_id' => ['required', 'string'],
            'start' => [
                'required',
                'after_or_equal:' . $start,
                'before_or_equal:' . $end,
            ],
            'end' => [
                'required',
                'before_or_equal:' . $end,
                'after_or_equal:' . $start,
            ],
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
        if ($this->input('id') || $this->input('sub_appointment_id')) {
            $subAppointment = $this->getSubAppointment();
            $this->merge([
                'appointment_id' => $subAppointment->appointment_id,
                'company_id' => $subAppointment->company_id,
            ]);
            return;
        }
        $appointment = $this->getAppointment();
        $this->merge([
            'appointment_id' => $appointment->id,
            'company_id' => $appointment->company_id,
        ]);
    }

    /**
     * Get SubAppointment based on supplied input
     *
     * @return SubAppointment
     */
    public function getSubAppointment()
    {
        if ($this->subAppointment) {
            return $this->subAppointment;
        }
        $id = $this->input('id') ?: $this->input('sub_appointment_id');
        $subAppointment = SubAppointment::with('appointment')->findOrFail($id);

        return $this->model = $this->subAppointment = $subAppointment;
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
        if ($this->isMethod('POST')) {
            $id = $this->input('appointment_id');
            return $this->appointment = Appointment::findOrFail($id);
        }
        $subAppointment = $this->getSubAppointment();

        return $this->appointment = $subAppointment->appointment;
    }
}
