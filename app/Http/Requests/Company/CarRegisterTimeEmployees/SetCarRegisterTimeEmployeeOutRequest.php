<?php

namespace App\Http\Requests\Company\CarRegisterTimeEmployees;

use App\Models\Car\CarRegisterTimeEmployee as AssignedEmployee;
use Illuminate\Foundation\Http\FormRequest;

class SetCarRegisterTimeEmployeeOutRequest extends FormRequest
{
    /**
     * AssignedEmployee object
     *
     * @var AssignedEmployee|null
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
            ->can('set driver car register time employees', $assignedEmployee);
    }

    /**
     * Get AssignedEmployee based on the supplied input
     *
     * @return AssignedEmployee
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
}
