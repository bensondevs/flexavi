<?php

namespace App\Http\Resources\Warranty;

use App\Http\Resources\Appointment\AppointmentResource;
use Illuminate\Http\Resources\Json\JsonResource;

class WarrantyAppointmentResource extends JsonResource
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
            'warranty_id' => $this->warranty_id,
        ];

        if ($this->relationLoaded('appointment')) {
            $structure['appointment'] = new AppointmentResource($this->appointment);
        }

        if ($this->relationLoaded('warrantyWorks')) {
            $structure['works'] = WarrantyAppointmentWorkResource::collection($this->warrantyWorks);
        }

        return $structure;
    }
}
