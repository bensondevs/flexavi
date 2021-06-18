<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

use App\Traits\ApiCollectionResource;

class AppointmentResource extends JsonResource
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
        return [
            'id' => $this->id,
            'company_id' => $this->company_id,
            'customer_id' => $this->customer_id,
            'start' => $this->start,
            'end' => $this->end,
            'include_weekend' => $this->include_weekend,
            'appointment_type' => $this->appointment_type,
            'appointment_status' => $this->appointment_status,
            'note' => $this->note,
        ];
    }
}
