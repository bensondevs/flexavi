<?php

namespace App\Http\Requests\Company\Employees;

use App\Models\Employee\Employee;
use Illuminate\Foundation\Http\FormRequest;

class DeleteEmployeeRequest extends FormRequest
{
    /**
     * Employee object
     *
     * @var  Employee|null
     */
    private ?Employee $employee = null;

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize(): bool
    {
        $employee = $this->getEmployee();

        return $this->user()
            ->fresh()
            ->can('delete-employee', $employee);
    }

    /**
     * Get Employee based on supplied input
     *
     * @return Employee|null
     */
    public function getEmployee(): ?Employee
    {
        if ($this->employee instanceof Employee) {
            return $this->employee;
        }

        $id = $this->input('employee_id');
        return $this->employee = Employee::withTrashed()->find($id);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array
    {
        return [];
    }
}
