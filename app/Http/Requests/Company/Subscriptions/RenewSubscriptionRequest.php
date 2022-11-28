<?php

namespace App\Http\Requests\Company\Subscriptions;

use App\Models\Subscription\SubscriptionPlanPeriod;
use App\Traits\CompanyInputRequest;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class RenewSubscriptionRequest extends FormRequest
{
    use CompanyInputRequest;

    /**
     * Subscription plan period object
     *
     * @var SubscriptionPlanPeriod|null
     */
    private ?SubscriptionPlanPeriod $subscriptionPlanPeriod = null;

    /**
     * Get Customer based on supplied input
     *
     * @return SubscriptionPlanPeriod|null
     */
    public function getSubscriptionPlanPeriod(): ?SubscriptionPlanPeriod
    {
        if ($this->subscriptionPlanPeriod instanceof SubscriptionPlanPeriod) {
            return $this->subscriptionPlanPeriod;
        }
        $id = $this->input('subscription_plan_period_id');

        return $this->subscriptionPlanPeriod = SubscriptionPlanPeriod::findOrFail($id);
    }

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        return [
            'subscription_plan_period_id' => ['required', 'string', Rule::exists('subscription_plan_periods', 'id')],
        ];
    }
}
