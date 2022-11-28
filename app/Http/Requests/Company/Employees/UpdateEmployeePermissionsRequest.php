<?php

namespace App\Http\Requests\Company\Employees;

use App\Models\Employee\Employee;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Http\FormRequest;

class UpdateEmployeePermissionsRequest extends FormRequest
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
        $user = $this->user()->fresh();
        $employee = $this->getEmployee();

        return $user->can('edit-employee', $employee);
    }

    /**
     * Handle permissions array input.
     *
     * @return void
     */
    protected function prepareForValidation(): void
    {
        if (is_array($this->input('permission_names'))) {
            return;
        }

        $permissionsArray = json_decode(
            $this->input('permission_names'),
            true
        );
        if ($permissionsArray) {
            $this->merge(['permission_names' => $permissionsArray]);
        }
    }

    /**
     * Get employee instance.
     *
     * @return Builder|Collection|Model|Builder[]
     */
    public function getEmployee(): Builder|array|Collection|Model
    {
        if ($this->employee instanceof Employee) {
            return $this->employee;
        }

        $employeeId = $this->input('employee_id');
        return $this->employee = Employee::findOrFail($employeeId);
    }

    /**
     * Get permissions submitted to the endpoint.
     *
     * @return array
     */
    public function permissions(): array
    {
        $permissions = $this->input('permission_names');
        return is_array($permissions) ?
            array_filter($permissions) : [$permissions];
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
            'permission_names' => ['required'], // Can be string or array
        ];
    }
}
