<?php

namespace App\Http\Requests\CarRegisterTimeEmployees;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Gate;

use App\Traits\CompanyInputRequest;

use App\Models\{ CarRegisterTime, Employee };

use App\Rules\OnlyOneDriver;

class AssignCarRegisterTimeEmployeeRequest extends FormRequest
{
    use CompanyInputRequest;

    private $time;
    private $employee;

    public function getCarRegisterTime()
    {
        if ($this->time) return $this->time;

        $id = $this->input('car_register_time_id');
        return $this->time = CarRegisterTime::findOrFail($id);
    }

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
        $time = $this->getCarRegisterTime();
        $employee = $this->getEmployee();
        return Gate::allows('assign-car-register-time-employee', [$time, $employee]);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $time = $this->getCarRegisterTime();
        return [
            'employee_id' => ['required']
            'passanger_type' => ['numeric', new OnlyOneDriver($time)],
        ];
    }
}
