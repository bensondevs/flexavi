<?php

namespace App\Http\Requests\Company\Workdays\Appointments;

use App\Models\{Appointment\Appointment, Workday\Workday};
use Illuminate\Foundation\Http\FormRequest;

class AttachAppointmentRequest extends FormRequest
{
    /**
     * Workday object
     *
     * @var Workday|null
     */
    private $workday;

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
        $workday = $this->getWorkday();
        $appointment = $this->getAppointment();

        return $this->user()
            ->fresh()
            ->can('attach-appointment-workday', [$workday, $appointment]);
    }

    /**
     * Get Workday based on supplied input
     *
     * @return Workday
     */
    public function getWorkday()
    {
        if ($this->workday) {
            return $this->workday;
        }
        $id = $this->input('workday_id');

        return $this->workday = Workday::findOrFail($id);
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
        return [];
    }
}
