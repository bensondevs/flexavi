<?php

namespace App\Http\Requests\PaymentPickups;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Gate;
use App\Traits\CompanyInputRequest;
use App\Models\{ Appointment, Employee };

class StorePaymentPickupRequest extends FormRequest
{
    use CompanyInputRequest;
    
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
     * Prepare inputted columns before validation
     * 
     * @return void
     */
    protected function prepareForValidation()
    {
        if (! $this->input('should_pickup_amount')) {
            $amount = $this->getAppointment()->works()->sum('total_price');
            $this->merge(['should_pickup_amount' => $amount]);
        }
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
        $this->setRules([
            'should_pickup_amount' => ['required', 'numeric'],
            'picked_up_amount' => ['numeric', 'nullable'],
            'reason_not_all' => [
                'string', 
                'nullable', 
                'required_unless:picked_up_amount,' . $this->input('should_pickup_amount')
            ],
            'should_picked_up_at' => ['nullable', 'date'],
            'picked_up_at' => ['nullable', 'date'],
        ]);

        return $this->returnRules();
    }
}
