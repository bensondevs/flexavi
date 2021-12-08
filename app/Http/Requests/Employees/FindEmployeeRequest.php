<?php

namespace App\Http\Requests\Employees;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Gate;

use App\Models\Employee;
use App\Traits\RequestHasRelations;

class FindEmployeeRequest extends FormRequest
{
    use RequestHasRelations;

    /**
     * List of loaded relation names
     * 
     * @var array
     */
    protected $relationNames = [
        'with_company' => true,
        'with_user' => false,
    ];

    /**
     * Employee model container
     * 
     * @var \App\Models\Employee|null
     */
    private $employee;

    /**
     * Get employee from the supplied input of 
     * `id` or `employee_id`
     * 
     * @return \App\Models\Employee|abort 404
     */
    public function getEmployee()
    {
        if ($this->employee) return $this->employee;

        $id = $this->input('id') ?: $this->input('employee_id');
        return $this->employee = Employee::findOrFail($id);
    }

    /**
     * Prepare inputtted data according to expected form
     * 
     * @return void
     */
    protected function prepareForValidation()
    {
        $this->prepareRelationInputs(); // Set $this->relationNames value
    }

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return Gate::allows('view-employee', $this->getEmployee());
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
