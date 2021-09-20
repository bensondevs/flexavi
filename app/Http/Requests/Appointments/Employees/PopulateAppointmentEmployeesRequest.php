<?php

namespace App\Http\Requests\Appointments\Employees;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Gate;

use App\Models\Employee;
use App\Models\Appointment;

use App\Traits\RequestHasRelations;
use App\Traits\CompanyPopulateRequestOptions;

class PopulateAppointmentEmployeesRequest extends FormRequest
{
    use RequestHasRelations;
    use CompanyPopulateRequestOptions;

    private $relationNames = [
        'with_appointment' => false,
        'with_employee' => true,
    ];

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
        return Gate::allows('view-any-employee');
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
        $this->addWhere([
            'column' => 'appointment_id',
            'value' => $this->getAppointment()->id,
        ]);

        $this->setWiths($this->relations());

        return $this->collectOptions();
    }
}
