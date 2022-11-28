<?php

namespace App\Http\Requests\Company\Appointments\Employees;

use App\Models\{Appointment\Appointment, User\User};
use Illuminate\Foundation\Http\FormRequest;

class AssignAppointmentEmployeeRequest extends FormRequest
{
    /**
     * User object
     *
     * @var User|null
     */
    private $user;

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
        $user = $this->getEmployee();
        $appointment = $this->getAppointment();

        return $this->user()
            ->fresh()
            ->can('assign-appointment-employee', [$appointment, $user]);
    }

    /**
     * Get Employee User based on supplied input
     *
     * @return User
     */
    public function getEmployee()
    {
        if ($this->user) {
            return $this->user;
        }
        $id = $this->input('user_id');

        return $this->user = User::findOrFail($id);
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
