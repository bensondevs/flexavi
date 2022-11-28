<?php

namespace App\Http\Resources\PaymentPickup;

use App\Http\Resources\Appointment\AppointmentResource;
use App\Http\Resources\Company\CompanyResource;
use App\Traits\ApiCollectionResource;
use Illuminate\Http\Resources\Json\JsonResource;

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
            'company_id' => $this->company_id,
            'appointment_id' => $this->appointment_id,

            'should_pickup_amount' => $this->should_pickup_amount,
            'formatted_should_pickup_amount' => $this->formatted_should_pickup_amount,

            'picked_up_amount' => $this->picked_up_amount,
            'formatted_picked_up_amount' => $this->formatted_picked_up_amount,

            'picked_up_at' => $this->picked_up_at,
        ];

        if ($this->relationLoaded('items')) {
            $structure['items'] = PaymentPickupItemResource::collection($this->items);
        }
        if ($this->relationLoaded('company')) {
            $structure['company'] = new CompanyResource($this->company);
        }

        if ($this->relationLoaded('appointment')) {
            $structure['appointment'] = new AppointmentResource($this->appointment);
        }

        return $structure;
    }
}
