<?php

namespace App\Http\Resources\Calculation;

use Illuminate\Http\Resources\Json\JsonResource;

class CalculationWorkResource extends JsonResource
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
            'quantity' => $this->quantity,
            'quantity_unit' => $this->quantity_unit,
            'description' => $this->description,
            'unit_price' => $this->unit_price,
            'total_price' => $this->total_price,
        ];
    }
}
