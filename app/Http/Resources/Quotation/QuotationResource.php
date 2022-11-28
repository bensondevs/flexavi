<?php

namespace App\Http\Resources\Quotation;

use App\Http\Resources\Company\CompanyResource;
use App\Http\Resources\Customer\CustomerResource;
use App\Models\Quotation\Quotation;
use App\Traits\ApiCollectionResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @mixin Quotation
 */
class QuotationResource extends JsonResource
{
    use ApiCollectionResource;

    /**
     * Transform the resource into an array.
     *
     * @param Request $request
     * @return array
     */
    public function toArray($request): array
    {
        $structure = [
            'id' => $this->id,
            'customer_id' => $this->customer_id,

            'date' => $this->date,
            'formatted_date' => $this->formatted_date,

            'expiry_date' => $this->expiry_date,
            'formatted_expiry_date' => $this->formatted_expiry_date,

            'note' => $this->note,
            'number' => $this->number,
            'customer_address' => $this->customer_address,

            'amount' => $this->amount,
            'formatted_amount' => $this->formatted_amount,

            'discount_amount' => $this->discount_amount,
            'formatted_discount_amount' => $this->formatted_discount_amount,

            'taxes' => $this->taxes,
            'total_taxes' => $this->total_taxes,
            'formatted_total_taxes' => $this->formatted_total_taxes,

            'overall_discount' => $this->overall_discount,
            'formatted_overall_discount' => $this->formatted_overall_discount,

            'total_amount' => $this->total_amount,
            'formatted_total_amount' => $this->formatted_total_amount,

            'potential_amount' => $this->potential_amount,
            'formatted_potential_amount' => $this->formatted_potential_amount,


            'status' => $this->status,
            'status_description' => $this->status_description,

            'signed_document' => $this->signed_document,
            'sent_at' => $this->sent_at,
            'signed_at' => $this->signed_at,
            'nullified_at' => $this->nullified_at,
        ];

        if ($this->relationLoaded('customer')) {
            $structure['customer'] = new CustomerResource($this->customer);
        }

        if ($this->relationLoaded('company')) {
            $structure['company'] = new CompanyResource($this->company);
        }

        if ($this->relationLoaded('items')) {
            $structure['items'] = QuotationItemResource::collection($this->items);
        }

        return $structure;
    }
}
