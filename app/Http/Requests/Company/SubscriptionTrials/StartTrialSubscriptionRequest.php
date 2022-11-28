<?php

namespace App\Http\Requests\Company\SubscriptionTrials;

use App\Traits\CompanyInputRequest;
use Illuminate\Foundation\Http\FormRequest;

class StartTrialSubscriptionRequest extends FormRequest
{
    use CompanyInputRequest;

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize(): bool
    {
        $company = $this->getCompany();
        if (!$company) {
            abort(422, 'Company not found');
        }

        return $this->user()
            ->fresh()
            ->can('start-trial-subscription', $company);

    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        return [
            //
        ];
    }
}
