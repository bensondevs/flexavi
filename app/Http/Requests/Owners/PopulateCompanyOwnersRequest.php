<?php

namespace App\Http\Requests\Owners;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Gate;

use App\Traits\RequestHasRelations;
use App\Traits\CompanyPopulateRequestOptions;

class PopulateCompanyOwnersRequest extends FormRequest
{
    use RequestHasRelations;
    use CompanyPopulateRequestOptions;

    private $relationNames = [
        'with_addresses' => true,
    ];

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return Gate::allows('view-any-owner');
    }

    protected function prepareForValidation()
    {
        $this->prepareRelationInputs();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'page' => ['integer', 'min:1'],
            'search' => ['string'],
        ];
    }

    public function options()
    {
        return $this->collectCompanyOptions();
    }
}