<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

use App\Traits\ApiCollectionResource;

class InvoiceResource extends JsonResource
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
        $referenceable = $this->referenceable;

        if ($this->referenceable_type == 'App\Models\Quotation') {
            $reference = new QuotationResource($referenceable);
        } else if ($this->referenceable_type == 'App\Models\Appointment') {
            $reference = new AppointmentResource($referenceable);
        }

        return [
            'id' => $this->id,
            'total' => number_format($this->total, 2),
            'formatted_total' => $this->formatted_total,
            'status' => $this->status,
            'status_description' => $this->status_description,
            'payment_method' => $this->payment_method,
            'payment_method_description' => $this->payment_method_description,
            // 'items' => $this->items,
            // 'payment_terms' => PaymentTermResource::collection($this->paymentTerms),
            // 'reference_type' => $this->referenceable_type,
            // 'reference' => $reference,
        ];
    }
}
