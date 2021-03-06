<?php

namespace App\Http\Requests\PaymentPickups;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Gate;
use App\Traits\{
    RequestHasRelations,
    CompanyPopulateRequestOptions
};
use App\Models\PaymentPickup;

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
        'with_employee' => true,
        'with_company' => false,
    ];

    /**
     * Prepare input to load the relationships
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
        return Gate::allows('view-any-payment-pickup');
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
}
