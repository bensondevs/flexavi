<?php

namespace App\Http\Requests\Employees;

use Illuminate\Foundation\Http\FormRequest;

use App\Models\Company;

class PopulateEmployeesRequest extends FormRequest
{
    private $company;

    public function getCompany()
    {
        return $this->company = $this->company ?:
            Company::findOrFail($this->get('company_id'));
    }

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        $user = $this->user();
        $company = $this->getCompany();

        return $user->hasCompanyPermission($company->id);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'company_id' => ['required', 'string'];
        ];
    }

    public function options()
    {
        /*
            Relations
        */
        $withs = [];

        /*
            Conditions
        */
        $wheres = [];
        array_push($wheres, [
            'column' => 'company_id', 
            'value' => $this->getCompany()->id
        ]);

        /*
            Condition Relations
        */
        $whereHases = [];

        return [
            'withs' => $withs,
            'wheres' => $wheres,
            'where_hases' => $whereHases,
        ];
    }
}
