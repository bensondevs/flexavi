<?php

namespace App\Http\Requests\Appointments\Employees;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Gate;

use App\Models\{Appointment, Employee};

class AssignAppointmentEmployeeRequest extends FormRequest
{
    private $employee;
    private $appointment;

    public function getEmployee()
    {
        if ($this->employee) return $this->employee;

        $id = $this->input('employee_id');
        return $this->employee = Employee::findOrFail($id);
    }

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
        $employee = $this->getEmployee();
        $appointment = $this->getAppointment();

        return Gate::allows('assign-appointment-employee', [$appointment, $employee]);
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
