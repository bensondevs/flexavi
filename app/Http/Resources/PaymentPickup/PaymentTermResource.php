<?php

namespace App\Http\Resources\PaymentPickup;

use App\Traits\ApiCollectionResource;
use Illuminate\Http\Resources\Json\JsonResource;

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
            'id' => $this->id,
            'term_name' => $this->term_name,
            'status' => $this->status,
            'status_description' => $this->status_description,
            'amount' => $this->amount,
            'formatted_amount' => $this->formatted_amount,
            'invoice_portion' => $this->invoice_portion,
            'formatted_invoice_portion' => $this->formatted_invoice_portion,
            'due_date' => $this->due_date,
            'formatted_due_date' => $this->formatted_due_date,
        ];
    }
}
