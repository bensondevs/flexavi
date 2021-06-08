<?php

namespace App\Http\Requests\Employees;

use Illuminate\Foundation\Http\FormRequest;

use App\Rules\AmongStrings;

use App\Models\Employee;

use App\Traits\CompanyInputRequest;

class SaveEmployeeRequest extends FormRequest
{
    use CompanyInputRequest;

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
        return $this->authorizeCompanyAction('employees');
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $types = Employee::getTypeValues();
        $statuses = Employee::getStatusValues();

        $this->setRules([
            'user_id' => ['string', 'exists:users,id'],
            'title' => ['required', 'string'],
            'employee_type' => ['required', 'string', new AmongStrings($types)],
            'employment_status' => ['string', new AmongStrings($statuses)],
        ]);

        return $this->returnRules();
    }
}
