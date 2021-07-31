<?php

namespace App\Http\Requests\AppointmentWorkers;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Gate;

use App\Models\Appointment;

use App\Traits\CompanyPopulateRequestOptions;

class PopulateAppointmentWorkersRequest extends FormRequest
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

        return Gate::allows('view-any-appointment-worker', $appointment);
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
