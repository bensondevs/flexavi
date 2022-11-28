<?php

namespace App\Http\Resources\Subscription;

use App\Models\Subscription\SubscriptionPlan;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @mixin SubscriptionPlan
 */
class SubscriptionPlanResource extends JsonResource
{
    /**
     * @param Request $request
     * @return array
     */
    public function toArray($request): array
    {
        $structure = [
            'id' => $this->id,
            'name' => $this->name,
            'base_price' => $this->base_price,
            'formatted_base_price' => $this->formatted_base_price,
            'description' => $this->description,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];

        if ($this->relationLoaded('subscriptionPlanPeriods')) {
            $structure['subscription_plan_periods'] = SubscriptionPlanPeriodResource::collection($this->subscriptionPlanPeriods);
        }
        return $structure;
    }
}
