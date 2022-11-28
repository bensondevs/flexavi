<?php

namespace App\Http\Requests\Company\Owners;

use App\Traits\CompanyPopulateRequestOptions;
use App\Traits\RequestHasRelations;
use Illuminate\Foundation\Http\FormRequest;

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
        'with_address' => true,
        'with_user' => true,
    ];

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize(): bool
    {
        return $this->user()
            ->fresh()
            ->can('view-any-owner');
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'page' => ['nullable','integer', 'min:1'],
            'search' => [ 'nullable','string'],
        ];
    }

    /**
     * Get populate options
     *
     * @return array
     */
    public function options(): array
    {
        if ($keyword = $this->get('keyword', $this->get('search'))) {
            $this->setSearch($keyword);
            $this->setSearchScope('table_scope_only');
        }

        return $this->collectCompanyOptions();
    }

    /**
     * Prepare input to load the relationships
     *
     * @return void
     */
    protected function prepareForValidation(): void
    {
        $this->prepareRelationInputs();
    }
}
