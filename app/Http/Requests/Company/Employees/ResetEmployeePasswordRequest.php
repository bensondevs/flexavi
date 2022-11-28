<?php

namespace App\Http\Requests\Company\Employees;

use App\Models\Employee\Employee;
use Illuminate\Foundation\Http\FormRequest;

class ResetEmployeePasswordRequest extends FormRequest
{
    /**
     * Employee instance container property
     *
     * @var Employee|null
     */
    private ?Employee $employee = null;

    /**
     * Get employee instance.
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
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize(): bool
    {
        $employee = $this->getEmployee();

        return $this->user()
            ->refresh()
            ->can('edit-employee', $employee);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        return [
            'employee_id' => ['required', 'exists:employees,id'],
            'password' => ['required', 'string'],
            'confirm_password' => ['required', 'same:password'],
        ];
    }
}
