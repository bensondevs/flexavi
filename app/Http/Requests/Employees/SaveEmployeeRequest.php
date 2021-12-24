<?php

namespace App\Http\Requests\Employees;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Gate;

use App\Models\Employee;
use App\Enums\Employee\EmployeeType;
use App\Traits\CompanyInputRequest;

class SaveEmployeeRequest extends FormRequest
{
    use CompanyInputRequest;

    /**
     * Found employee container
     * 
     * @var \App\Models\Employee
     */
    private $employee;

    /**
     * Get employee from the supplied input of
     * "employee_id" or "id"
     * 
     * @return \App\Models\Employee
     */
    public function getEmployee()
    {
        if ($this->employee) return $this->employee;

        $id = $this->input('id') ?: $this->input('employee_id');
        return $this->employee = $this->model = Employee::findOrFail($id);
    }

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        if ($this->isMethod('POST')) {
            return Gate::allows('create-employee');
        }

        return Gate::allows('edit-employee', $this->getEmployee());
    }

    /**
     * Prepare input before validation
     * 
     * @return void
     */
    protected function prepareForValidation()
    {
        if ($type = $this->employee_type) {
            if (! is_numeric($this->employee_type)) {
                $type = ucfirst($type);
                $available = EmployeeType::getKeys();
                if (! in_array($type, $available)) {
                    return abort(422, json_encode([
                        'message' => 'Employee Type not available. available: ' . json_encode($available)
                    ]));
                }
                $type = EmployeeType::getValue($type);
            }
            $this->merge(['employee_type' => $type]);
        }

        if ($status = $this->employment_status) {
            if (! is_numeric($this->employment_status)) {
                $status = ucfirst($type);
                $available = EmployementStatus::getKeys();
                if (! in_array($type, $available)) {
                    return abort(422, json_encode([
                        'message' => 'Employeement Status not available. available: ' . json_encode($available)
                    ]));
                }
                $status = constant('App\Enums\Employee\EmploymentStatus::' . $status);
            }
            $this->merge(['employement_status' => $status]);
        }
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $this->setRules([
            'user_id' => ['string', 'exists:users,id'],
            'title' => ['required', 'string'],
            'employee_type' => ['required', 'integer', 'min:1', 'max:2'],
            'employment_status' => ['integer', 'min:1', 'max:3'],
        ]);

        return $this->returnRules();
    }
}
