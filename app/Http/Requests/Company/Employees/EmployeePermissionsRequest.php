<?php

namespace App\Http\Requests\Company\Employees;

use App\Models\Employee\Employee;
use Illuminate\Foundation\Http\FormRequest;

class EmployeePermissionsRequest extends FormRequest
{
    /**
     * Employee instance container property.
     *
     * @var Employee|null
     */
    private ?Employee $employee = null;

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize(): bool
    {
        return $this->user()
            ->fresh()
            ->can('edit-employee', $this->getEmployee());
    }

    /**
     * Get employee instance.
     *
     * @return Employee
     */
    public function getEmployee(): Employee
    {
        if ($this->employee instanceof Employee) {
            return $this->employee;
        }

        $employeeId = $this->input('employee_id');
        return $this->employee = Employee::findOrFail($employeeId);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        return [
            'employee_id' => ['required', 'string', 'exists:employees,id'],
        ];
    }
}
