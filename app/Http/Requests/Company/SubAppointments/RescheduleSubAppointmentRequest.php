<?php

namespace App\Http\Requests\Company\SubAppointments;

use App\Models\Appointment\SubAppointment;
use App\Traits\CompanyInputRequest;
use Illuminate\Foundation\Http\FormRequest;

class RescheduleSubAppointmentRequest extends FormRequest
{
    use CompanyInputRequest;

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
        $subAppointment = $this->getSubAppointment();

        return $this->user()
            ->fresh()
            ->can('reschedule-sub-appointment', $subAppointment);
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
        $id = $this->input('id');

        return $this->subAppointment = SubAppointment::with(
            'appointment'
        )->findOrFail($id);
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
            'end' => ['required', 'before_or_equal:' . $appointment->end],
        ]);

        return $this->returnRules();
    }
}
