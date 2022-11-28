<?php

namespace App\Http\Requests\Company\Employees;

use App\Enums\Employee\{EmployeeType, EmploymentStatus};
use App\Models\Employee\Employee;
use App\Rules\Helpers\Media;
use App\Traits\CompanyInputRequest;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class SaveEmployeeRequest extends FormRequest
{
    use CompanyInputRequest;

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
        $user = $this->user()->fresh();

        if ($this->isMethod('POST')) {
            return $user->can('create-employee');
        }

        return $user->can('edit-employee', $this->getEmployee());
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
        return $this->employee = Employee::findOrFail($id);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array
    {
        $this->setRules([
            'title' => ['required', 'string'],
            'employee_type' => [
                'required',
                'integer',
                'min:' . EmployeeType::Administrative,
                'max:' . EmployeeType::Roofer,
            ],
            'employment_status' => [
                'required',
                'integer',
                Rule::in(EmploymentStatus::getValues()),
            ],
            'fullname' => ['required', 'string'],
            'birth_date' => ['required', 'date'],
            'contract_file' => [
                'nullable',
                'file',
                'max:' . Media::MAX_DOCUMENT_SIZE,
                'mimes:' . Media::documentExtensions(),
            ],
        ]);

        return $this->returnRules();
    }

    /**
     * Prepare input before validation
     *
     * @return void
     */
    protected function prepareForValidation(): void
    {
        if (!is_int($this->input('employee_type'))) {
            $this->merge(['employee_type' => (int) $this->input('employee_type')]);
        }

        if (!is_int($this->input('employee_status'))) {
            $this->merge(['employee_status' => (int) $this->input('employee_status')]);
        }
    }
}
