<?php

namespace App\Http\Requests\Company\Employees;

use App\Enums\Employee\EmploymentStatus;
use App\Models\Employee\Employee;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateEmployeeStatusRequest extends FormRequest
{
    /**
     * Employee object
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
        $user = $this->user()->fresh();

        return $user->can('edit-employee', $this->getEmployee());
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
        return $this->employee = Employee::findOrFail($id);
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
            'status' => ['required', 'integer', Rule::in(EmploymentStatus::getValues())],
        ];
    }
}
