<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

use App\Traits\ApiCollectionResource;

class PaymentTermResource extends JsonResource
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
        return [
            'term_name' => $this->term_name,
            'status' => $this->status,
            'status_description' => $this->status_description,
            'amount' => number_format($this->amount, 2),
            'due_date' => $this->due_date,
            'human_due_date' => $this->human_due_date,
        ];
    }
}