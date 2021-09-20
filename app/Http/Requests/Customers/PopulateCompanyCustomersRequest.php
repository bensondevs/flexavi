<?php

namespace App\Http\Requests\Customers;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Gate;

use App\Traits\RequestHasRelations;
use App\Traits\CompanyPopulateRequestOptions;

class PopulateCompanyCustomersRequest extends FormRequest
{
    use RequestHasRelations;
    use CompanyPopulateRequestOptions;

    protected $relationNames = [
        'with_company' => false,
    ];

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return Gate::allows('view-any-customer');
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

    public function options()
    {
        if ($relations = $this->relations()) {
            $this->setWiths($relations);
        }

        return $this->collectCompanyOptions();
    }
}
