<?php

namespace App\Http\Requests\Company\SubscriptionPlans;

use App\Traits\PopulateRequestOptions;
use Illuminate\Foundation\Http\FormRequest;

class PopulateSubscriptionPlansRequest extends FormRequest
{
    use PopulateRequestOptions;

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize(): bool
    {
        return $this->user()
            ->fresh()
            ->can('view-any-subscription-plan');
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array
    {
        return [
            //
        ];
    }

    /**
     * Get the request options.
     *
     * @return array
     */
    public function options(): array
    {
        $this->addWhere([
            'column' => 'is_trial',
            'value' => false
        ]);

        return $this->collectOptions();
    }


}
