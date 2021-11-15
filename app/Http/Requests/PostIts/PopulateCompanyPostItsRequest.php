<?php

namespace App\Http\Requests\PostIts;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Gate;

use App\Traits\RequestHasRelations;
use App\Traits\CompanyPopulateRequestOptions;

class PopulateCompanyPostItsRequest extends FormRequest
{
    use RequestHasRelations;
    use CompanyPopulateRequestOptions;

    private $relationNames = [
        'with_company' => false,
        'with_user' => true,
        'with_assigned_users' => true,
    ];

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return Gate::allows('view-any-post-it');
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
            //
        ];
    }

    public function options()
    {
        $relations = $this->relations();
        $this->setWiths($relations);
        
        return $this->collectCompanyOptions();
    }
}
