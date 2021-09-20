<?php

namespace App\Http\Requests\Appointments\Employees;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Gate;

use App\Models\AppointmentEmployee;

class UnassignAppointmentEmployeeRequest extends FormRequest
{
    private $appointmentEmployee;

    public function getAppointmentEmployee()
    {
        if ($this->appointmentEmployee) {
            return $this->appointmentEmployee;
        }

        $id = $this->input('appointment_employee_id');
        $appointmentEmployee = AppointmentEmployee::findOrFail($id);
        return $this->appointmentEmployee = $appointmentEmployee;
    }

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        $appointmentEmployee = $this->getAppointmentEmployee();
        return Gate::allows('unassign-appointment-employee', $appointmentEmployee);
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
}
