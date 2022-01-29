<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

use App\Traits\ApiCollectionResource;

class SubscriptionResource extends JsonResource
{
    use ApiCollectionResource;

    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        $structure = [
            'company_id' => $this->company_id,
            'subscription_plan_id' => $this->subscription_plan_id,
            'status' => $this->status,
            'status_description' => $this->status_description,
            'subscription_start' => $this->subscription_start,
            'subscription_end' => $this->subscription_end,
        ];

        if ($this->relationLoaded('company')) {
            $company = new CompanyResource($this->company);
            $structure['company'] = $company;
        }

        if ($this->relationLoaded('plan')) {
            $plan = new SubscriptionPlanResource($this->plan);
            $structure['plan'] = $plan;
        }

        if ($this->relationLoaded('payment')) {
            $payment = new SubscriptionPaymentResource($this->payment);
            $structure['payment'] = $payment;
        }

        return $structure;
    }
}
