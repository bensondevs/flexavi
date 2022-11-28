<?php

namespace App\Http\Requests\Company\Inspections;

use App\Models\Employee\Employee;
use App\Traits\CompanyPopulateRequestOptions;
use App\Traits\RequestHasRelations;
use Illuminate\Foundation\Http\FormRequest;

class PopulateEmployeeInspectionsRequest extends FormRequest
{
    use CompanyPopulateRequestOptions, RequestHasRelations;

    /**
     * List of loadable relation names
     *
     * @var array
     */
    protected $relationNames = [
        'with_appointment' => false,
        'with_pictures' => false,
        'with_customer' => false,
    ];

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
            ->can('view-any-employee-inspection', $employee);
    }

    /**
     * Get Employee based on supplid input
     *
     * @return Employee
     */
    public function getEmployee()
    {
        if ($this->employee instanceof Employee)
            return $this->employee;

        $id = $this->input('employee_id');

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

    /**
     * Get options
     *
     * @return array
     */
    public function options()
    {
        foreach ($this->getRelations() as $relationName) {
            $this->addWith($relationName);
        }

        $this->addWhereHas("inspectors", [
            [
                'column' => "employee_id",
                'value' => $this->getEmployee()->id
            ]
        ]);

        return $this->collectCompanyOptions();
    }
}
