<?php

namespace App\Http\Requests\CarRegisterTimeEmployees;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Gate;

use App\Models\CarRegisterTimeEmployee as AssignedEmployee;

class UnassignCarRegisterTimeEmployeeRequest extends FormRequest
{
    private $assignedEmployee;

    public function getAssignedEmployee()
    {
        if ($this->assignedEmployee) return $this->assignedEmployee;

        $id = $this->input('car_register_time_employee_id') ?: 
            $this->input('assigned_employee_id');
        return $this->assignedEmployee = AssignedEmployee::findOrFail($id);
    }

    protected function prepareForValidation()
    {
        $force = strtobool($this->input('force'));
        return $this->merge(['force' => $force]);
    }

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        $assignedEmployee = $this->getAssignedEmployee();
        return Gate::allows('unassign-car-register-time-employee', $assignedEmployee);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'force' => ['boolean'],
        ];
    }
}
