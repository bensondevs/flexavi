<?php

namespace App\Http\Requests\Company\Subscriptions;

use App\Models\Company\Company;
use App\Traits\CompanyPopulateRequestOptions;
use App\Traits\RequestHasRelations;
use Illuminate\Foundation\Http\FormRequest;

class PopulateCompanySubscriptionsRequest extends FormRequest
{
    use CompanyPopulateRequestOptions, RequestHasRelations;

    /**
     * List of configurable relationships
     *
     * @var array
     */
    protected array $relationNames = [
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
            ->can('view-any-subscription');
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array
    {
        return [];
    }

    /**
     * Get the options
     *
     * @return array
     */
    public function options(): array
    {
        $relations = $this->relations();
        $this->setWiths($relations);

        $this->addOrderBy('created_at', 'DESC');

        $this->addWhere([
            'column' => 'owner_type',
            'value' => Company::class,
        ]);

        $this->addWhere([
            'column' => 'owner_id',
            'value' => $this->getCompany()->id,
        ]);

        return $this->collectOptions();
    }
}
