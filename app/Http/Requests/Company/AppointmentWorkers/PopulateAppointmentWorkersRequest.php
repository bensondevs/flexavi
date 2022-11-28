<?php

namespace App\Http\Requests\Company\AppointmentWorkers;

use App\Models\Appointment\Appointment;
use App\Traits\CompanyPopulateRequestOptions;
use Illuminate\Foundation\Http\FormRequest;

class PopulateAppointmentWorkersRequest extends FormRequest
{
    use CompanyPopulateRequestOptions;

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
            ->can('view-any-appointment-worker', $appointment);
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
        return [
            'appointment_id' => ['required', 'string'],
        ];
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
            'value' => $this->input('appointment_id'),
        ]);
        $this->addWith('employee');

        return $this->collectCompanyOptions();
    }
}
