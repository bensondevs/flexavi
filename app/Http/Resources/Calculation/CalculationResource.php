<?php

namespace App\Http\Resources\Calculation;

use Illuminate\Http\Resources\Json\JsonResource;

class CalculationResource extends JsonResource
{
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
            'calculation' => $this->calculation,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];

        return $structure;
    }
}
