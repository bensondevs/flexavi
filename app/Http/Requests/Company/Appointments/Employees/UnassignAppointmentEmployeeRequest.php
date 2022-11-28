<?php

namespace App\Http\Requests\Company\Appointments\Employees;

use App\Models\Appointment\AppointmentEmployee;
use Illuminate\Foundation\Http\FormRequest;

class UnassignAppointmentEmployeeRequest extends FormRequest
{
    /**
     * AppointmentEmployee object
     *
     * @var AppointmentEmployee|null
     */
    private $appointmentEmployee;

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        $appointmentEmployee = $this->getAppointmentEmployee();

        return $this->user()
            ->fresh()
            ->can('unassign-appointment-employee', $appointmentEmployee);
    }

    /**
     * Get AppointmentEmployee based on supplied input
     *
     * @return AppointmentEmployee
     */
    public function getAppointmentEmployee()
    {
        if ($this->appointmentEmployee) {
            return $this->appointmentEmployee;
        }
        $id = $this->input('appointment_employee_id') ?: $this->input('id');
        $appointmentEmployee = AppointmentEmployee::findOrFail($id);

        return $this->appointmentEmployee = $appointmentEmployee;
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
