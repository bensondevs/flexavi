<?php

namespace App\Http\Requests\Company\Warranties;

use App\Traits\CompanyPopulateRequestOptions;
use App\Traits\RequestHasRelations;
use Illuminate\Foundation\Http\FormRequest;

class PopulateWarrantiesRequest extends FormRequest
{
    use CompanyPopulateRequestOptions;
    use RequestHasRelations;

    /**
     * Warranty relationship configuration
     *
     * @var array
     */
    private $relationNames = [
        'with_company' => false,
        'with_warrantyAppointments' => false,
        'with_work' => false,
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
            ->can('view-any-warranty');
    }

    /**
     * Get options for querying the data
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
     * Prepare input before validation
     *
     * This will handle relationship
     *
     * @return void
     */
    protected function prepareForValidation()
    {
        $this->prepareRelationInputs();
    }
}
