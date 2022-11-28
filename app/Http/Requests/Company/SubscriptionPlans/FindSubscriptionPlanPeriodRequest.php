<?php

namespace App\Http\Requests\Company\SubscriptionPlans;

use App\Models\Subscription\SubscriptionPlanPeriod;
use Illuminate\Foundation\Http\FormRequest;

class FindSubscriptionPlanPeriodRequest extends FormRequest
{

    /**
     * Solution instance container property.
     *
     * @var SubscriptionPlanPeriod|null
     */
    private ?SubscriptionPlanPeriod $subscriptionPlanPeriod = null;

    /**
     * Get subscription plan based on the supplied input
     *
     * @param bool $force
     * @return SubscriptionPlanPeriod|null
     */
    public function getSubscriptionPlanPeriod(bool $force = false): ?SubscriptionPlanPeriod
    {
        if ($this->subscriptionPlanPeriod instanceof SubscriptionPlanPeriod and not($force)) {
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
        return $this->user()
            ->fresh()
            ->can('view-any-subscription-plan');
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return @return array<string, array<int, string>>
     */
    public function rules(): array
    {
        return [
            //
        ];
    }
}
