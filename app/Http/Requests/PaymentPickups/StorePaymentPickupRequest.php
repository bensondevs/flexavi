<?php

namespace App\Http\Requests\PaymentPickups;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Gate;
use App\Models\{ Appointment, Employee };

class StorePaymentPickupRequest extends FormRequest
{
    /**
     * Selected appointment set for payment pickup
     * 
     * @var \App\Models\Appointment
     */
    private $appointment;

    /**
     * Selected employee set for payment pickup
     * 
     * @var \App\Models\Employee
     */
    private $employee;

    /**
     * Get selected appointment from supplied input of `appointment_id`
     * 
     * @return \App\Models\Appointment
     */
    public function getAppointment()
    {
        if ($this->appointment) return $this->appointment;

        $id = $this->input('appointment_id');
        return $this->appointment = Appointment::findOrFail($id);
    }

    /**
     * Get selected employee from supplied input of `employee_id`
     * 
     * @return \App\Models\Employee
     */
    public function getEmployee()
    {
        if ($this->employee) return $this->employee;

        $id = $this->input('employee_id');
        return $this->employee = Employee::findOrFail($id);
    }

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        $appointment = $this->getAppointment();
        $employee = $this->getEmployee();
        return Gate::allows('create-payment-pickup', [$appointment, $employee]);
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
