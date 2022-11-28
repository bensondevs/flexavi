<?php

namespace App\Http\Resources\Subscription;

use App\Models\Subscription\SubscriptionPlanPeriod;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @mixin SubscriptionPlanPeriod
 */
class SubscriptionPlanPeriodResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param Request $request
     * @return array
     */
    public function toArray($request): array
    {
        $structure = [
            'id' => $this->id,
            'subscription_plan_id' => $this->subscription_plan_id,
            'name' => $this->name,
            'description' => $this->description,
            'amount' => $this->amount,
            'formatted_amount' => $this->formatted_amount,
        ];

        if ($this->relationLoaded('subscriptionPlan')) {
            $structure['subscription_plan'] = new SubscriptionPlanResource($this->subscriptionPlan);
        }

        return $structure;
    }
}
