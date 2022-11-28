<?php

namespace App\Http\Resources\Calculation;

use Illuminate\Http\Resources\Json\JsonResource;

class CalculationCostResource extends JsonResource
{
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
            'cost_name' => $this->cost_name,
            'amount' => $this->amount,
            'paid_amount' => $this->paid_amount,
            'unpaid_amount' => $this->unpaid_amount,
        ];
    }
}
