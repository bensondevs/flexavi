<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

use App\Traits\ApiCollectionResource;

use App\Http\Resources\CompanyResource;
use App\Http\Resources\AppointmentResource;

class AppointmentCostResource extends JsonResource
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
            'id' => $this->id,
            'cost_name' => $this->cost_name,
            'cost' => $this->cost,
            'paid_cost' => $this->paid_cost,
            'unpaid_cost' => $this->unpaid_cost,
            'receipt' => $this->receipt_url,
        ];

        if ($this->relationLoaded('company')) {
            $structure['company'] = new CompanyResource($this->company);
        }

        if ($this->relationLoaded('appointment')) {
            $structure['appointment'] = new AppointmentResource($this->appointment);
        }

        return $structure;
    }
}
