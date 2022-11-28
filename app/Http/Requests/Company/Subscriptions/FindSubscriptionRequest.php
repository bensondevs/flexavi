<?php

namespace App\Http\Requests\Company\Subscriptions;

use App\Models\Subscription\Subscription;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class FindSubscriptionRequest extends FormRequest
{
    /**
     * Subscription object
     *
     * @var  Subscription|null
     */
    private $subscription = null;

    /**
     * Get Car based on the supplied input
     *
     * @return Subscription|null
     */
    public function getSubscription(): ?Subscription
    {
        if ($this->subscription instanceof Subscription) {
            return $this->subscription;
        }
        $id = $this->input('subscription_id');

        return $this->subscription = Subscription::withTrashed()->findOrFail($id);
    }

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize(): bool
    {
        return $this->user()
            ->fresh()
            ->can('view-subscription');
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        return [
            'subscription_id' => ['required', Rule::exists('subscriptions', 'id')],
        ];
    }
}
