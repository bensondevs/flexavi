<?php

namespace App\Http\Requests\Company\Mandates;

use App\Traits\CompanyPopulateRequestOptions;
use App\Traits\InputRequest;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class SaveMandateRequest extends FormRequest
{
    use InputRequest;
    use CompanyPopulateRequestOptions;

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $this->setRules([
            'method' => ['required', Rule::in(['paypal', 'directdebit'])],
            'consumer_name' => ['required'],
            'consumer_iban' => ['required_if:method,==,directdebit'],
            'consumer_bic' => ['nullable'],
            'consumer_email' => ['required_if:method,==,paypal'],
            'signature_date' => ['nullable'],
            'mandate_reference' => ['nullable'],
            'paypal_billing_agreement_id' => ['required_if:method,==,paypal'],
            'company_id' => ['required', 'exists:companies,id'],
            'user_id' => ['required', 'exists:users,id'],
        ]);

        return $this->returnRules();
    }

    /**
     * Prepare input requests before validation
     *
     * @return void
     */
    protected function prepareForValidation()
    {
        $company = $this->getCompany();
        $user = $this->user();
        $this->merge([
            'user_id' => $user->id,
            'company_id' => $company->id,
        ]);
    }
}
