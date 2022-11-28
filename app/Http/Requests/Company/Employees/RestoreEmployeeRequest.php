<?php

namespace App\Http\Requests\Company\Employees;

use App\Models\Employee\Employee;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Http\FormRequest;

class RestoreEmployeeRequest extends FormRequest
{
    /**
     * Employee object
     *
     * @var Employee|null
     */
    private ?Employee $trashedEmployee = null;

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize(): bool
    {
        $trashedEmployee = $this->getTrashedEmployee();

        return $this->user()
            ->fresh()
            ->can('restore-employee', $trashedEmployee);
    }

    /**
     * Get Employee based on supplied input
     *
     * @return Builder|Collection|Model|\Illuminate\Database\Query\Builder|Builder[]|\Illuminate\Database\Query\Builder[]
     */
    public function getTrashedEmployee(): Builder|array|Collection|Model|\Illuminate\Database\Query\Builder
    {
        if ($this->trashedEmployee instanceof Employee) {
            return $this->trashedEmployee;
        }

        $id = $this->input('employee_id');
        $employee = Employee::onlyTrashed()->findOrFail($id);
        return $this->trashedEmployee = $employee;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array
    {
        return [
            //
        ];
    }
}
