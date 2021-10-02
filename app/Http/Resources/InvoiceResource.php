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
        $structure = [
            'id' => $this->id,
            'company_id' => $this->company_id,
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
        ];

        if ($this->invoice_number) {
            $structure['invoice_number'] = $this->invoice_number;
        }

        if ($this->relationLoaded('items')) {
            $structure['items'] = InvoiceItemResource::collection($this->items);
        }

        if ($this->relationLoaded('paymentTerms')) {
            $terms = $this->paymentTerms;
            $structure['payment_terms'] = PaymentTermResource::collection($terms);
        }

        if ($this->relationLoaded('company')) {
            $structure['company'] = new CompanyResource($this->company);
        }

        if ($this->relationLoaded('invoiceable')) {
            if ($invoiceable = $this->invoiceable) {
                $pureClass = get_pure_class($invoiceable);
                $className = get_lower_class($invoiceable);
                $resourceClass = '\\App\Http\\Resources\\' . $pureClass . 'Resource';

                $structure[$className] = new $resourceClass($invoiceable);
            }
        }

        return $structure;
    }
}
