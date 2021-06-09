<?php

namespace App\Http\Requests\Employees;

use Illuminate\Foundation\Http\FormRequest;

use App\Models\Employee;

class DeleteEmployeeRequest extends FormRequest
{
    private $employee;

    public function getEmployee()
    {
        return $this->employee = $this->employee ?:
            Employee::withTrashed()->findOrFail($this->input('id'));
    }

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        $user = $this->user();
        $employee = $this->getEmployee();

        return $user->hasCompanyPermission($employee->company_id, 'delete employees');
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
