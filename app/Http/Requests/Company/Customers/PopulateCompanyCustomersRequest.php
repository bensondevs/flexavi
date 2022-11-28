<?php

namespace App\Http\Requests\Company\Customers;

use App\Traits\CompanyPopulateRequestOptions;
use App\Traits\RequestHasRelations;
use Illuminate\Foundation\Http\FormRequest;

class PopulateCompanyCustomersRequest extends FormRequest
{
    use RequestHasRelations;
    use CompanyPopulateRequestOptions;

    /**
     * Define the relation names
     *
     * @var array
     */
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
        return $this->user()
            ->fresh()
            ->can('view-any-customer');
    }

    /**
     * Collect any options from request
     *
     * @return array
     */
    public function options()
    {
        if ($relations = $this->relations()) {
            $this->setWiths($relations);
        }

        if ($keyword = $this->get('keyword', $this->get('search', null))) {
            $this->setSearch($keyword);
            $this->setSearchScope('table_scope_only');
        }

        if ($this->has('cities')) {
            $cities = array_map(
                fn ($city) => rtrim($city),
                explode(',', $this->get('cities'))
            );

            $this->addWhereHasWhereIn('address', [
                [
                    'column' => 'city',
                    'values' => $cities
                ]
            ]);
        }

        $this->addWith('address');

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
}
