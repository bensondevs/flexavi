<?php

namespace App\Http\Requests\Employees;

use Illuminate\Foundation\Http\FormRequest;

use App\Rules\UniqueWithConditions;

use App\Models\Employee;

use App\Traits\InputRequest;

class SaveEmployeeRequest extends FormRequest
{
    use InputRequest;

    private $employee;

    public function getEmployee()
    {
        return $this->employee = $this->model = $this->employee ?: 
            Employee::findOrFail($this->input('id'));
    }

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        $currentUser = auth()->user();

        if ($this->isMethod('POST'))
            return $currentUser->hasCompanyPermission($this->input('company_id'));

        $employee = $this->getEmployee();
        return $currentUser->hasCompanyPermission($employee->company_id);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $this->setRules([
            'company_id' => ['required', 'string', 'exists:companies,id'],
            'user_id' => [
                'required', 
                'string', 
                'exists:users,id', 
                new UniqueWithConditions(
                    new Employee,
                    ['company_id' => $this->input('company_id')] 
                )],
            'title' => ['required', 'string'],
            'employee_type' => ['required', 'string'],
            'employment_status' => ['required', 'string'],
        ]);

        return $this->returnRules();
    }
}
