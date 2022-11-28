<?php

namespace App\Http\Requests\Company\PaymentPickups;

use App\Traits\{CompanyPopulateRequestOptions, RequestHasRelations};
use Illuminate\Foundation\Http\FormRequest;

class PopulateCompanyPaymentPickupsRequest extends FormRequest
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
    protected $relationNames = [
        'with_appointment' => true,
        'with_items' => false,
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
            ->can('view-any-payment-pickup');
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
     * Prepare input to load the relationships
     *
     * @return void
     */
    protected function prepareForValidation()
    {
        $this->prepareRelationInputs();
        $this->setWiths($this->getRelations());
    }
}
