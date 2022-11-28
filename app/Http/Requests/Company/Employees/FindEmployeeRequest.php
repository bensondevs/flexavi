<?php

namespace App\Http\Requests\Company\Employees;

use App\Models\Employee\Employee;
use App\Traits\{InputRequest, RequestHasRelations};
use Illuminate\Foundation\Http\FormRequest;

class FindEmployeeRequest extends FormRequest
{
    use InputRequest;
    use RequestHasRelations;

    /**
     * List of loaded relation names
     *
     * @var array
     */
    protected array $relationNames = [
        'with_company' => true,
        'with_user' => true,
        'with_address' => false,
    ];

    /**
     * Employee object
     *
     * @var Employee|null
     */
    private ?Employee $employee = null;

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize(): bool
    {
        $employee = $this->getEmployee();

        return $this->user()
            ->fresh()
            ->can('view-employee', $employee);
    }

    /**
     * Prepare inputted data according to expected form
     *
     * @return void
     */
    protected function prepareForValidation(): void
    {
        $this->prepareRelationInputs();
    }

    /**
     * Get Employee based on supplied input
     *
     * @return Employee|null
     */
    public function getEmployee(): ?Employee
    {
        if ($this->employee instanceof Employee) {
            return $this->employee;
        }

        $id = $this->input('employee_id');
        $employee = Employee::findOrFail($id);

        $relations = $this->getRelations();
        $employee->load($relations);

        return $this->employee = $employee;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array
    {
        return [];
    }
}
