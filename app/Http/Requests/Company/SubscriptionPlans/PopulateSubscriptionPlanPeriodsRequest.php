<?php

namespace App\Http\Requests\Company\SubscriptionPlans;

use App\Models\Subscription\SubscriptionPlan;
use App\Traits\PopulateRequestOptions;
use Illuminate\Foundation\Http\FormRequest;

class PopulateSubscriptionPlanPeriodsRequest extends FormRequest
{
    use PopulateRequestOptions;

    /**
     * Solution instance container property.
     *
     * @var SubscriptionPlan|null
     */
    private ?SubscriptionPlan $subscriptionPlan = null;

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
            'column' => 'subscription_plan_id',
            'value' => $this->getSubscriptionPlan()->id
        ]);

        return $this->collectOptions();
    }

    /**
     * Get subscription plan based on the supplied input
     *
     * @param bool $force
     * @return SubscriptionPlan|null
     */
    public function getSubscriptionPlan(bool $force = false): ?SubscriptionPlan
    {
        if ($this->subscriptionPlan instanceof SubscriptionPlan and not($force)) {
            return $this->subscriptionPlan;
        }

        $id = $this->input('subscription_plan_id');
        return $this->subscriptionPlan = SubscriptionPlan::findOrFail($id);
    }
}
