<?php

namespace App\Http\Requests\Employees;

use Illuminate\Foundation\Http\FormRequest;

use App\Traits\CompanyInputRequest;

use App\Models\Employee;

class RestoreEmployeeRequest extends FormRequest
{
    use CompanyInputRequest;

    private $trashedEmployee;

    public function getTrashedEmployee()
    {
        return $this->trashedEmployee = $this->trashedEmployee ?:
            Employee::withTrashed()->findOrFail($this->input('id'));
    }

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        $employee = $this->getTrashedEmployee();
        return $this->checkCompanyPermission('restore employees', $employee);
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
