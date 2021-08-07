<?php

namespace App\Http\Requests\Costs;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Gate;

use App\Traits\CompanyPopulateRequestOptions;

use App\Models\Appointment;

class PopulateAppointmentCostsRequest extends FormRequest
{
    use CompanyPopulateRequestOptions;

    private $appointment;

    public function getAppointment()
    {
        if ($this->appointment) return $this->appointment;

        $id = $this->input('appointment_id');
        return $this->appointment = Appointment::findOrFail($id);
    }

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        $appointment = $this->getAppointment();
        return Gate::allows('view-any-appointment-cost', $appointment);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            //
        ];
    }

    public function options()
    {
        $this->addWhereHas('appointments', [
            [
                'column' => 'appointments.id',
                'value' => $this->getAppointment()->id,
            ]
        ]);

        if ($withAppointment = $this->input('with_appointment')) {
            $this->addWith('appointments');
        }

        if ($withWorklist = $this->input('with_worklist')) {
            $this->addWith('worklists');
        }

        if ($withWorkday = $this->input('with_workday')) {
            $this->addWith('workdays');
        }

        return $this->collectCompanyOptions();
    }
}
