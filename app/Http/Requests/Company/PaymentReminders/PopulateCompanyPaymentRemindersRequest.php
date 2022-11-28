<?php

namespace App\Http\Requests\Company\PaymentReminders;

use App\Traits\{CompanyPopulateRequestOptions, RequestHasRelations};
use Illuminate\Foundation\Http\FormRequest;

class PopulateCompanyPaymentRemindersRequest extends FormRequest
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
            ->can('view-all-payment-reminder');
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
     * Prepare input for validation
     *
     * @return void
     */
    protected function prepareForValidation()
    {
        $this->prepareRelationInputs();
    }
}
