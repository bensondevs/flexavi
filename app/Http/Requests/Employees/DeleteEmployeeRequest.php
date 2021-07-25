<?php

namespace App\Http\Requests\Employees;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Gate;

use App\Models\Employee;

class DeleteEmployeeRequest extends FormRequest
{
    private $employee;

    public function getEmployee()
    {
        if ($this->employee) return $this->employee;

        $id = $this->input('id') ?: $this->input('employee_id');
        return $this->employee = Employee::withTrashed()->findOrFail($id);
    }

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        $employee = $this->getEmployee();
        return Gate::allows('delete-employee', $employee);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            
        ];
    }
}
