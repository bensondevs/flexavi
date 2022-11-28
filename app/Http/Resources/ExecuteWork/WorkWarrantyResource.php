<?php

namespace App\Http\Resources\ExecuteWork;

use App\Http\Resources\WorkService\WorkServiceResource;
use Illuminate\Http\Resources\Json\JsonResource;

class WorkWarrantyResource extends JsonResource
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

            'quantity' => $this->quantity,
            'quantity_unit' => $this->quantity_unit,
            'quantity_with_unit' => $this->quantity . ' ' . $this->quantity_unit,


            'unit_price' => $this->unit_price,
            'formatted_unit_price' => $this->formatted_unit_price,

            'total_price' => $this->total_price,
            'formatted_total_price' => $this->formatted_total_price,

            'total_paid' => $this->total_paid,
            'formatted_total_paid' => $this->formatted_total_paid,

            'warranty_time_value' => $this->warranty_time_value,

            'warranty_time_type' => $this->warranty_time_type,
            'warranty_time_type_description' => $this->warranty_time_type_description,

            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];

        if ($this->relationLoaded('workService')) $structure['work_service'] = new WorkServiceResource($this->workService);

        return $structure;
    }
}
