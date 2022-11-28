<?php

namespace App\Http\Requests\Company\CarRegisterTimeEmployees;

use App\Models\Car\CarRegisterTimeEmployee as AssignedEmployee;
use App\Traits\RequestHasRelations;
use Illuminate\Foundation\Http\FormRequest;

class FindCarRegisterTimeEmployeesRequest extends FormRequest
{
    use RequestHasRelations;

    /**
     * List configuration for relationship loaded
     *
     * @var array
     */
    private $relationNames = [
        'with_employee' => true,
    ];

    /**
     * AssignedEmployee object
     *
     * @var  AssignedEmployee|null
     */
    private $assignedEmployee;

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        $assignedEmployee = $this->getAssignedEmployee();

        return $this->user()
            ->fresh()
            ->can('view car register time employees', $assignedEmployee);
    }

    /**
     * Get AssignedEmployee based on supplied input
     *
     * @return  AssignedEmployee
     */
    public function getAssignedEmployee()
    {
        if ($this->assignedEmployee) {
            return $this->assignedEmployee;
        }
        $id =
            $this->input('car_register_time_employee_id') ?:
                $this->input('assigned_employee_id');

        return $this->assignedEmployee = AssignedEmployee::findOrFail($id);
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

    /**
     * Prepare inputs for validation
     *
     * @return void
     */
    protected function prepareForValidation()
    {
        $this->prepareRelationInputs();
    }
}
