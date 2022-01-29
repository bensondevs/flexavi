<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

use App\Traits\ApiCollectionResource;

class SubscriptionPaymentResource extends JsonResource
{
    use ApiCollectionResource;

    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        $structure = [
            'user_id' => $this->user_id,
            'company_id' => $this->company_id,
            'subscription_id' => $this->subscription_id,
            'status' => $this->status,
            'status_description' => $this->status_description,
            'payment_method' => $this->payment_method,
            'payment_method_description' => $this->payment_method_description,
            'amount' => $this->amount,
        ];

        if ($this->relationLoaded('user')) {
            $structure['user'] = new UserResource($this->user);
        }

        if ($this->relationLoaded('company')) {
            $company = new CompanyResource($this->company);
            $structure['company'] = $company;
        }

        if ($this->relationLoaded('subscription')) {
            $subscription = new SubscriptionResource($this->subscription);
            $structure['subscription'] = $subscription;
        }

        return $structure;
    }
}
