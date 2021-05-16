<?php

namespace App\Http\Requests\AppointmentWorkers;

use Illuminate\Foundation\Http\FormRequest;

use App\Models\Appointment;

use App\Traits\CompanyPopulateRequestOptions;

class PopulateAppointmentWorkersRequest extends FormRequest
{
    use CompanyPopulateRequestOptions;

    private $appointment;

    public function getAppointment()
    {
        return $this->appointment = $this->model = $this->appointment ?:
            Appointment::findOrFail($this->input('appointment_id'));
    }

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        $user = auth()->user();
        $appointment = $this->getAppointment();

        return $user->hasCompanyPermission($appointment->company_id);
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

        return $this->collectCompanyOptions();
    }
}
