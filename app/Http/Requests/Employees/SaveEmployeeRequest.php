<?php

namespace App\Http\Requests\Employees;

use Illuminate\Foundation\Http\FormRequest;

class SaveEmployeeRequest extends FormRequest
{
    private $employee;

    public function getEmployee()
    {
        return $this->employee = $this->employee ?: 
            Employee::findOrFail(request()->input('id'));
    }

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        $employee = $this->getEmployee();
        $currentUser = auth()->user();

        return $currentUser->hasCompanyPermission($employee->company_id);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $rules = [
            'user_id' => ['required', 'string', 'exists:users,id'],
            'company_id' => ['required', 'string', 'exists:companies,id'],
            'title' => ['required', 'string'],
            'employee_type' => ['required', 'string'],
            'employee_status' => ['required', 'string'],
        ];

        if (request()->isMethod('PUT') || request()->isMethod('PATCH')) {
            $employee = $this->getEmployee();
        }

        return $rules;
    }

    public function onlyInRules()
    {
        return $this->only(array_keys($this->rules()));
    }
}
