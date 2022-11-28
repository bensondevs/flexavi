<?php

namespace App\Http\Requests\Company\Costs\Appointments;

use App\Models\Appointment\Appointment;
use App\Traits\CompanyPopulateRequestOptions;
use Illuminate\Foundation\Http\FormRequest;

class PopulateAppointmentCostsRequest extends FormRequest
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
        return $this->user()
            ->fresh()
            ->can('view-any-cost');
    }

    /**
     * Get options
     *
     * @return array
     */
    public function options()
    {
        $this->addWhereHas('appointments', [
            [
                'column' => 'appointments.id',
                'value' => $this->getAppointment()->id,
            ],
        ]);
        if ($this->input('with_appointment')) {
            $this->addWith('appointments');
        }
        if ($this->input('with_worklist')) {
            $this->addWith('worklists');
        }
        if ($this->input('with_workday')) {
            $this->addWith('workdays');
        }

        return $this->collectCompanyOptions();
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
