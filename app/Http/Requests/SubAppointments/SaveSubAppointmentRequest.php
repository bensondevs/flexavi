<?php

namespace App\Http\Requests\SubAppointments;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Gate;

use App\Models\Appointment;
use App\Models\SubAppointment;

use App\Enums\SubAppointment\SubAppointmentStatus;
use App\Enums\SubAppointment\SubAppointmentCancellationVault;

use App\Traits\CompanyInputRequest;

class SaveSubAppointmentRequest extends FormRequest
{
    use CompanyInputRequest;

    private $appointment;
    private $subAppointment;

    public function getAppointment()
    {
        if ($this->appointment) return $this->appointment;

        if ($this->isMethod('POST')) {
            $id = $this->input('appointment_id');
            return $this->appointment = Appointment::findOrFail($id);
        }

        $subAppointment = $this->getSubAppointment();
        return $this->appointment = $subAppointment->appointment;
    }

    public function getSubAppointment()
    {
        if ($this->subAppointment) return $this->subAppointment;

        $id = $this->input('id') ?: $this->input('sub_appointment_id');
        $subAppointment = SubAppointment::with('appointment')->findOrFail($id);
        return $this->model = $this->subAppointment = $subAppointment;
    }

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
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        if (! $this->isMethod('POST')) {
            $subAppointment = $this->getSubAppointment();
            return Gate::allows('edit-sub-appointment', $subAppointment);
        }

        $appointment = $this->getAppointment();
        return Gate::allows('create-sub-appointment', $appointment);
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
            'start' => ['required', 'after_or_equal:' . $start, 'before_or_equal:' . $end],
            'end' => ['required', 'before_or_equal:' . $end, 'after_or_equal:' . $start],
        ]);

        return $this->returnRules();
    }
}
