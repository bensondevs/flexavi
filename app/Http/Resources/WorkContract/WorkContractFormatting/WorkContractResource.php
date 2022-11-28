<?php

namespace App\Http\Resources\WorkContract\WorkContractFormatting;

use App\Http\Resources\Customer\CustomerResource;
use App\Models\WorkContract\WorkContract;
use App\Traits\ApiCollectionResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/** @mixin WorkContract */
class WorkContractResource extends JsonResource
{
    use ApiCollectionResource;

    /**
     * @param Request $request
     * @return array
     */
    public function toArray($request): array
    {


        $structure = [
            'id' => $this->id,
            'company_id' => $this->company_id,
            'customer_id' => $this->customer_id,
            'number' => $this->number,
            'status' => $this->status,
            'status_description' => $this->status_description,
            'footer' => $this->formatted_footer,

            'amount' => $this->amount,
            'formatted_amount' => $this->formatted_amount,

            'taxes' => $this->taxes,
            'total_taxes' => $this->total_taxes,
            'formatted_total_taxes' => $this->formatted_total_taxes,

            'discount_amount' => $this->discount_amount,
            'formatted_discount_amount' => $this->formatted_discount_amount,

            'potential_amount' => $this->potential_amount,
            'formatted_potential_amount' => $this->formatted_potential_amount,

            'total_amount' => $this->total_amount,
            'formatted_total_amount' => $this->formatted_total_amount,
            'signed_document' => $this->signed_document,
            'signature_url' => $this->signature_url,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];

        if ($this->relationLoaded('forewordContents')) {
            $structure['foreword_contents'] = WorkContractContentResource::collection($this->forewordContents);
        }

        if ($this->relationLoaded('contractContents')) {
            $structure['contract_contents'] = WorkContractContentResource::collection($this->contractContents);
        }

        if ($this->relationLoaded('customer')) {
            $structure['customer'] = new CustomerResource($this->customer);
        }

        if ($this->relationLoaded('services')) {
            $structure['services'] = WorkContractServiceResource::collection($this->services);
        }


        return $structure;
    }
}
