<?php

namespace App\Http\Resources\Warranty;

use App\Http\Resources\Work\WorkResource;
use Illuminate\Http\Resources\Json\JsonResource;

class WarrantyWorkResource extends JsonResource
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
            'warranty_id' => $this->id,
            'work_id' => $this->work_id,

            'status' => $this->status,
            'status_description' => $this->status_description,

            'amount' => $this->amount,
            'formatted_amount' => $this->formatted_amount,
        ];

        if ($this->relationLoaded('warranty')) {
            $warranty = new WarrantyResource($this->warranty);
            $structure['warranty'] = $warranty;
        }

        if ($this->relationLoaded('work')) {
            $work = new WorkResource($this->work);
            $structure['work'] = new WorkResource($work);
        }

        return $structure;
    }
}
