<?php

namespace App\Http\Requests\Company\SubscriptionPayments;

use App\Models\Subscription\Subscription;
use App\Traits\CompanyPopulateRequestOptions;
use Illuminate\Foundation\Http\FormRequest;

class PopulatePaymentRequest extends FormRequest
{
    use CompanyPopulateRequestOptions;

    /**
     * Subscription object
     *
     * @var Subscription|null
     */
    private $subscription;

    /**
     * Get Subscription based on supplied input
     *
     * @return Subscription
     */
    public function getSubscription()
    {
        if ($this->subscription) {
            return $this->subscription;
        }
        $id = $this->input('id') ?: $this->input('subscription_id');

        return $this->subscription = Subscription::findOrFail($id);
    }

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
        return [];
    }
}
