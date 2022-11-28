<?php

namespace App\Http\Resources\Warranty;

use App\Http\Resources\ExecuteWork\WorkWarrantyResource;
use Illuminate\Http\Resources\Json\JsonResource;

class WarrantyAppointmentWorkResource extends JsonResource
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
            'work_warranty_id' => $this->work_warranty_id,
            'customer_paid' => $this->customer_paid,
            'company_paid' => $this->company_paid,
        ];

        if ($this->relationLoaded('work')) {
            $structure['work'] = new WorkWarrantyResource($this->work);
        }

        return $structure;
    }
}
