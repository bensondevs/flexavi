<?php

namespace App\Http\Resources\Invoice;

use App\Http\Resources\WorkService\WorkServiceResource;
use App\Models\Invoice\InvoiceItem;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/** @mixin InvoiceItem */
class InvoiceItemResource extends JsonResource
{
    /**
     * @param Request $request
     * @return array
     */
    public function toArray($request): array
    {
        $structure = [
            'id' => $this->id,
            'invoice_id' => $this->invoice_id,
            'work_service_id' => $this->work_service_id,
            'amount' => $this->amount,
            'unit_price' => $this->unit_price,
            'total' => $this->total,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];

        if ($this->relationLoaded('workService')) {
            $structure['work_service'] = new WorkServiceResource($this->workService);
        }

        if ($this->relationLoaded('invoice')) {
            $structure['invoice'] = new InvoiceResource($this->invoice);
        }

        return $structure;
    }
}
