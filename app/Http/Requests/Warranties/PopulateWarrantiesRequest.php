<?php

namespace App\Http\Requests\Warranties;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Gate;

use App\Traits\RequestHasRelations;
use App\Traits\CompanyPopulateRequestOptions;

class PopulateWarrantiesRequest extends FormRequest
{
    use CompanyPopulateRequestOptions;
    use RequestHasRelations;

    private $relationNames = [
        'with_company' => false,
        'with_appointment' => false,
        'with_work' => false,
    ];

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return Gate::allows('view-any-warranty');
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
        return [];
    }

    public function options()
    {
        $relations = $this->relations();
        $this->setWiths($relations);

        return $this->collectCompanyOptions();
    }
}
