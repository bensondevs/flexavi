<?php

namespace App\Http\Requests\Employees;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Gate;

use App\Models\Employee;

class RestoreEmployeeRequest extends FormRequest
{
    /**
     * Found trashed employee
     * 
     * @var \App\Models\Employee
     */
    private $trashedEmployee;

    /**
     * Get trashed employee by supplied input of
     * "id" or "employee_id"
     * 
     * @return \App\Models\Employee
     */
    public function getTrashedEmployee()
    {
        if ($this->trashedEmployee) return $this->trashedEmployee;

        $id = $this->input('id') ?: $this->input('employee_id');
        return $this->trashedEmployee = Employee::onlyTrashed()->findOrFail($id);
    }

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        $employee = $this->getTrashedEmployee();
        return Gate::allows('restore-employee', $employee);
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
