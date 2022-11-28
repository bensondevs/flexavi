<?php

namespace App\Http\Resources\Cost;

use App\Traits\ApiCollectionResource;
use Illuminate\Http\Resources\Json\JsonResource;

class CostResource extends JsonResource
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
            'amount' => $this->amount,
            'paid_amount' => $this->paid_amount,
            'unpaid_amount' => $this->unpaid_amount,
        ];

        if ($this->relationLoaded('costables')) {
            $structure['costables'] = CostableResource::collection($this->costables);
        }

        return $structure;
    }
}
