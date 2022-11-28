<?php

namespace App\Http\Requests\Company\Warranties;

use App\Models\Employee\Employee;
use App\Traits\RequestHasRelations;
use Illuminate\Foundation\Http\FormRequest;

class PopulateEmployeeWarrantiesRequest extends FormRequest
{
    use RequestHasRelations;

    /**
     * Warranty relationship configuration
     *
     * @var array
     */
    private $relationNames = [
        'with_company' => false,
        'with_warrantyAppointments' => false,
        'with_work' => false,
    ];

    /**
     * Employee Model
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
            ->can('view-any-employee-warranty', $employee);
    }

    /**
     * Get Employee based on supplied input
     *
     * @return Employee
     */
    public function getEmployee()
    {
        if ($this->employee instanceof Employee)
            return $this->employee;

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
        return [
            //
        ];
    }
}
