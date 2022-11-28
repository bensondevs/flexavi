<?php

namespace App\Http\Resources\ExecuteWork;

use App\Http\Resources\Invoice\InvoiceResource;
use App\Http\Resources\Quotation\QuotationResource;
use Illuminate\Http\Resources\Json\JsonResource;

class ExecuteWorkRelatedMaterialResource extends JsonResource
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
            'related_quotation' => $this->related_quotation,
            'related_quotation_description' => $this->related_quotation_description,
            'related_invoice' => $this->related_invoice,
            'related_invoice_description' => $this->related_invoice_description,
            'related_work_contract' => $this->related_work_contract,
            'related_work_contract_description' => $this->related_work_contract_description,
            'quotation_id' => $this->quotation_id,
            'invoice_id' => $this->invoice_id,
            'quotation_file_url' => $this->quotation_file_url,
            'invoice_file_url' => $this->invoice_file_url,
            'work_contract_file_url' => $this->work_contract_file_url,
        ];

        if ($this->relationLoaded('invoice')) {
            $structure['invoice'] = new InvoiceResource($this->invoice);
        }

        if ($this->relationLoaded('quotation')) {
            $structure['quotation'] = new QuotationResource($this->quotation);
        }

        return $structure;
    }
}
