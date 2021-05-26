<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class InvoiceResource extends JsonResource
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
            'work_contract' => $this->workContract,
            'total' => $this->total,
            'payment_status' => $this->payment_status,
            'payment_method' => $this->payment_method,
            'items' => $this->items,
            'payment_terms' => $this->payment_terms,
        ];
    }
}
