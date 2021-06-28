<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

use App\Traits\ApiCollectionResource;

class WorkResource extends JsonResource
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

            'quantity' => $this->quantity,
            'quantity_unit' => $this->quantity_unit,
            'quantity_with_unit' => $this->quantity . ' ' . $this->quantity_unit,

            'description' => $this->description,
            'unit_price' => $this->unit_price,

            'unit_total' => $this->unit_total,
            'formatted_unit_total' => $this->formatted_unit_total,

            'include_tax' => $this->include_tax,
            
            'tax_percentage' => $this->tax_percentage,
            'formatted_tax_percentage' => $this->formatted_tax_percentage,

            'tax_amount' => $this->tax_amount,
            'formatted_tax_amount' => $this->formatted_tax_amount,

            'total_price' => $this->total_price,
            'formatted_total_price' => $this->formatted_total_price,
        ];

        return $structure;
    }
}
