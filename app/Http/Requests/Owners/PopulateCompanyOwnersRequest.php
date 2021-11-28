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

    /**
     * List of relationships that will be loaded
     * Set the attribute to true, it will load the relationship
     * upon the response
     * 
     * @var array
     */
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

    /**
     * Prepare input to load the relationships
     * 
     * @return void
     */
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

    /**
     * Get populate options.
     * 
     * This functions include queries where, with and etc...
     * 
     * @return array
     */
    public function options()
    {
        return $this->collectCompanyOptions();
    }
}