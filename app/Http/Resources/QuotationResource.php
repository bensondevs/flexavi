<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class QuotationResource extends JsonResource
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
            'creator' => $this->creator,
            'customer' => $this->customer,

            'subject' => $this->subject,

            'quotation_number' => $this->quotation_number,
            'quotation_type' => $this->quotation_type,
            'quotation_description' => $this->quotation_description,

            'pdf_url' => $this->pdf_url,
            'expiry_date' => $this->expiry_date,
            'status' => $this->status,
            'payment_method' => $this->payment_method,
        ];
    }
}
