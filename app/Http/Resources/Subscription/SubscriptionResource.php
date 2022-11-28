<?php

namespace App\Http\Resources\Subscription;

use App\Models\Subscription\Subscription;
use App\Traits\ApiCollectionResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/** @mixin Subscription */
class SubscriptionResource extends JsonResource
{
    use ApiCollectionResource;

    /**
     * @param Request $request
     * @return array
     */
    public function toArray($request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'plan' => $this->plan,
            'owner_type' => $this->owner_type,
            'owner_id' => $this->owner_id,
            'next_plan' => $this->next_plan,
            'quantity' => $this->quantity,
            'tax_percentage' => $this->tax_percentage,
            'ends_at' => $this->ends_at,
            'trial_ends_at' => $this->trial_ends_at,
            'cycle_started_at' => $this->cycle_started_at,
            'cycle_ends_at' => $this->cycle_ends_at,
            'scheduled_order_item_id' => $this->scheduled_order_item_id,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'redeemed_coupons_count' => $this->redeemed_coupons_count,
            'order_items_count' => $this->order_items_count,
            'applied_coupons_count' => $this->applied_coupons_count,
            'cycle_left' => $this->cycle_left,
            'cycle_progress' => $this->cycle_progress,
            'latest_processed_order_item_count' => $this->latest_processed_order_item_count,
            'currency' => $this->currency,
        ];
    }
}
