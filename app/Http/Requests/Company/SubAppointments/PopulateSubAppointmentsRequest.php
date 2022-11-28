<?php

namespace App\Http\Requests\Company\SubAppointments;

use App\Models\Appointment\Appointment;
use App\Traits\PopulateRequestOptions;
use Illuminate\Foundation\Http\FormRequest;

class PopulateSubAppointmentsRequest extends FormRequest
{
    use PopulateRequestOptions;

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
            ->can('view-any-sub-appointment', $appointment);
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
     * Get options
     *
     * @return array
     */
    public function options()
    {
        $this->addWhere([
            'column' => 'appointment_id',
            'operator' => '=',
            'value' => $this->getAppointment()->id,
        ]);

        return $this->collectOptions();
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
