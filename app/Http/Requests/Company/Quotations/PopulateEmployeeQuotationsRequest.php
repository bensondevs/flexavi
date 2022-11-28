<?php

namespace App\Http\Requests\Company\Quotations;

use App\Models\Employee\Employee;
use App\Traits\CompanyPopulateRequestOptions;
use Illuminate\Foundation\Http\FormRequest;

class PopulateEmployeeQuotationsRequest extends FormRequest
{
    use CompanyPopulateRequestOptions;

    /**
     * Employee object
     *
     * @var Employee|null
     */
    private $employee;

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        $employee = $this->getEmployee();

        return $this->user()
            ->fresh()
            ->can('view-any-employee-quotation', $employee);
    }

    /**
     * Get Employee based on supplid input
     *
     * @return Employee
     */
    public function getEmployee()
    {
        if ($this->employee) {
            return $this->employee;
        }
        $id = $this->input('employee_id') ?: $this->input('id');

        return $this->employee = Employee::findOrFail($id);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [];
    }
}
