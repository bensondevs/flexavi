<?php

namespace App\Http\Requests\Subscriptions;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Gate;

use App\Traits\CompanyInputRequest;
use App\Enums\SubscriptionPayment\{
    SubscriptionPaymentMethod as PaymentMethod
};

class PurchaseSubscriptionRequest extends FormRequest
{
    use CompanyInputRequest;

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        $company = $this->getCompany();
        return Gate::allows('purchase-subscription', $company);
    }

    /**
     * Prepare input before validation
     * 
     * @return void
     */
    protected function prepareForValidation()
    {
        if (! $this->has('company_id')) {
            $company = $this->getCompany();
            $this->merge(['company_id' => $company->id]);
        }
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'company_id' => ['required', 'exists:companies,id'],
            'subscription_plan_id' => [
                'required', 
                'exists:subscription_plans,id'
            ],
            'payment_method' => [
                'required',
                'min:' . PaymentMethod::Cash,
                'max:' . PaymentMethod::PaymentGateway,
            ],
        ];
    }
}
