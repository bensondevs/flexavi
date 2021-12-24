<?php

namespace App\Http\Requests\PaymentReminders;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Gate;
use App\Traits\{
    RequestHasRelations,
    CompanyPopulateRequestOptions
};
use App\Models\PaymentReminder;

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
     * Prepare input for validation
     * 
     * @return void
     */
    protected function prepareForValidation()
    {
        $this->prepareRelationInputs();
    }

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return Gate::allows('view-all-payment-reminder');
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

    /**
     * Request options, contains query and any relation
     * loaded when result is queries
     * 
     * @return array
     */
    public function options()
    {
        //
    }
}
