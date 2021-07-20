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
        // Set reference
        $referenceable = $this->referenceable;
        if ($this->referenceable_type == 'App\Models\Quotation') {
            $reference = new QuotationResource($referenceable);
        } else if ($this->referenceable_type == 'App\Models\Appointment') {
            $reference = new AppointmentResource($referenceable);
        }

        $structure = [
            'id' => $this->id,
            // 'company_id' => $this->company_id,
            'total' => $this->total,
            'formatted_total' => $this->formatted_total,
            'total_in_terms' => $this->total_in_terms,
            'formatted_total_in_terms' => $this->formatted_total_in_terms,
            'total_out_terms' => $this->total_in_terms,
            'formatted_total_out_terms' => $this->formatted_total_in_terms,
            'total_paid' => $this->total_paid,
            'formatted_total_paid' => $this->formatted_total_paid,
            'total_unpaid' => $this->total_unpaid,
            'formatted_total_unpaid' => $this->formatted_total_unpaid,
            'status' => $this->status,
            'status_description' => $this->status_description,
            'payment_method' => $this->payment_method,
            'payment_method_description' => $this->payment_method_description,
            // 'items' => $this->items,
            // 'payment_terms' => PaymentTermResource::collection($this->paymentTerms),
            // 'reference_type' => $this->referenceable_type,
            // 'reference' => $reference,
        ];

        if ($this->invoice_number) {
            $structure['invoice_number'] = $this->invoice_number;
        }

        return $structure;
    }
}
