<?php

namespace App\Http\Resources\PaymentPickup;

use App\Http\Resources\Invoice\InvoiceResource;
use Illuminate\Http\Resources\Json\JsonResource;

class PaymentPickupItemResource extends JsonResource
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
            'payment_pickup_id' => $this->payment_pickup_id,
            'invoice_id' => $this->invoice_id,

            'total_bill' => $this->total_bill,
            'formatted_total_bill' => $this->formatted_total_bill,

            'pickup_amount' => $this->pickup_amount,
            'formatted_pickup_amount' => $this->formatted_pickup_amount,

            'note' => $this->note,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,

        ];

        if ($this->relationLoaded('invoice')) {
            $structure['invoice'] = new InvoiceResource($this->invoice);
        }

        $structure['payment_terms'] = PaymentTermResource::collection($this->payment_terms);

        return $structure;
    }
}
