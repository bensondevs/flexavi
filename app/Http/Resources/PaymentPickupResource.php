<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Traits\ApiCollectionResource;

class PaymentPickupResource extends JsonResource
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

        if ($this->relationLoaded('company')) {
            $structure['company'] = new CompanyResource($this->company);
        }

        if ($this->relationLoaded('appointment')) {
            $structure['appointment'] = new AppointmentResource($this->appointment);
        }


        if ($this->relationLoaded('pickupables')) {
            $structure['pickupables'] = $this->pickupables;
        }

        return $structure;
    }
}