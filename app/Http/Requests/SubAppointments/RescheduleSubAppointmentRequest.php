<?php

namespace App\Http\Requests\SubAppointments;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Gate;

use App\Models\Appointment;
use App\Models\SubAppointment;

use App\Traits\CompanyInputRequest;

class RescheduleSubAppointmentRequest extends FormRequest
{
    use CompanyInputRequest;

    private $subAppointment;

    public function getSubAppointment()
    {
        if ($this->subAppointment) return $this->subAppointment;

        $id = $this->input('id');
        return $this->subAppointment = SubAppointment::with('appointment')->findOrFail($id);
    }

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return Gate::allows('reschedule-sub-appointment', $this->getSubAppointment());
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $subAppointment = $this->getSubAppointment();
        $appointment = $subAppointment->appointment;

        $this->setRules([
            'start' => ['required', 'after_or_equal:' . now()],
            'end' => ['required', 'before_or_equal' . $appointment->end],
        ]);

        return $this->returnRules();
    }
}
