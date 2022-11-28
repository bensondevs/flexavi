<?php

namespace App\Http\Requests\Company\PostIts;

use App\Traits\CompanyPopulateRequestOptions;
use App\Traits\RequestHasRelations;
use Illuminate\Foundation\Http\FormRequest;

class PopulateCompanyPostItsRequest extends FormRequest
{
    use RequestHasRelations;
    use CompanyPopulateRequestOptions;

    /**
     * Default relation loaded
     *
     * @var array
     */
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
        return $this->user()
            ->fresh()
            ->can('view-any-post-it');
    }

    /**
     * Populate options
     *
     * @return array
     */
    public function options()
    {
        $relations = $this->relations();
        $this->setWiths($relations);

        return $this->collectCompanyOptions();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [];
    }

    /**
     * Prepare for validation
     *
     * @return void
     */
    protected function prepareForValidation()
    {
        $this->prepareRelationInputs();
    }
}
