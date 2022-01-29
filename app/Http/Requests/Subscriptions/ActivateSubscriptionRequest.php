<?php

namespace App\Http\Requests\Subscriptions;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Gate;

use App\Models\Subscription;

class ActivateSubscriptionRequest extends FormRequest
{
    /**
     * Target subscription
     * 
     * @var \App\Models\Subscription|null
     */
    private $subscription;

    /**
     * Get target subscription
     * 
     * @return \App\Models\Subscription|abort 404
     */
    public function getSubscription()
    {
        if ($this->Subscription) {
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
        $subscription = $this->getSubscription();
        return Gate::allows('activate-subscription', $subscription);
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
}
