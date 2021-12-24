<?php

namespace App\Http\Requests\Employees;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Gate;

use App\Traits\CompanyPopulateRequestOptions;

class PopulateEmployeesRequest extends FormRequest
{
    use CompanyPopulateRequestOptions;

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return Gate::allows('view any employees');
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
     * The query options for the populate employees
     * 
     * @return array
     */
    public function options()
    {
        $this->addWith('user');
        $this->addWith('addresses');
        $this->addWithCount('inspections');

        return $this->collectCompanyOptions();
    }
}
