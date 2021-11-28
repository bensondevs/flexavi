<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class PaymentPickupResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        $structure = [
            'id' => $this->id,
            'appointment_id' => $this->appointment_id,
            'should_pickup_amount' => $this->should_pickup_amount,
            'picked_up_amount' => $this->picked_up_amount,
            'should_picked_up_at' => $this->should_picked_up_at,
            'picked_up_at' => $this->picked_up_at,
        ];

        if ($this->should_pickup_amount < $this->picked_up_amount) {
            $structure['reason_not_all'] = $this->reason_not_all;
        }

        if ($this->relationLoaded('appointment')) {
            //
        }

        if ($this->relationLoaded('employee')) {
            //
        }

        if ($this->relationLoaded('paymentPickupable')) {
            //
        }

        return $structure;
    }
}