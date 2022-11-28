<?php

namespace App\Http\Requests\Company\CarRegisterTimeEmployees;

use App\Models\{Car\CarRegisterTime, Employee\Employee};
use App\Rules\OnlyOneDriver;
use App\Traits\CompanyInputRequest;
use Illuminate\Foundation\Http\FormRequest;

class AssignCarRegisterTimeEmployeeRequest extends FormRequest
{
    use CompanyInputRequest;

    /**
     * CarRegisterTime object
     *
     * @var CarRegisterTime|null
     */
    private $time;

    /**
     * Employee object
     *
     * @var Employee|null
     */
    private $employee;

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        $time = $this->getCarRegisterTime();
        $employee = $this->getEmployee();

        return $this->user()
            ->fresh()
            ->can('assign car register time employees', [$time, $employee]);
    }

    /**
     * Get CarRegisterTime based on supplied input
     *
     * @return CarRegisterTime
     */
    public function getCarRegisterTime()
    {
        if ($this->time) {
            return $this->time;
        }
        $id = $this->input('car_register_time_id');

        return $this->time = CarRegisterTime::findOrFail($id);
    }

    /**
     * Get Employee based on supplied input
     *
     * @return Employee
     */
    public function getEmployee()
    {
        if ($this->employee) {
            return $this->employee;
        }
        $id = $this->input('employee_id');

        return $this->employee = Employee::findOrFail($id);
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
            'employee_id' => ['required'],
            'passanger_type' => ['numeric', new OnlyOneDriver($time)],
        ];
    }
}
