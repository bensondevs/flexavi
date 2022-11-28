<?php

namespace App\Http\Requests\Company\SubscriptionPlans;

use App\Models\Subscription\SubscriptionPlan;
use App\Traits\PopulateRequestOptions;
use Illuminate\Foundation\Http\FormRequest;

class FindSubscriptionPlanRequest extends FormRequest
{
    use PopulateRequestOptions;

    /**
     * Solution instance container property.
     *
     * @var SubscriptionPlan|null
     */
    private ?SubscriptionPlan $subscriptionPlan = null;

    /**
     * Get subscription plan based on the supplied input
     *
     * @param bool $force
     * @return SubscriptionPlan|null
     */
    public function getSubscriptionPlan(bool $force = false): ?SubscriptionPlan
    {
        $withs = [];
        if ($this->subscriptionPlan instanceof SubscriptionPlan and not($force)) {
            return $this->subscriptionPlan;
        }

        if ($this->input('with_subscription_plan_periods')) {
            $withs[] = 'subscriptionPlanPeriods';
        }

        $id = $this->input('subscription_plan_id');
        return $this->subscriptionPlan = SubscriptionPlan::with($withs)->findOrFail($id);
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
     * Collect options for the queries in repository
     *
     * @return array
     */
    public function options(): array
    {
        if ($this->input('with_subscription_plan_periods')) {
            $this->addWith('subscriptionPlanPeriods');
        }

        return $this->collectOptions();
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
}
