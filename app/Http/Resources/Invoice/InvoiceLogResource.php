<?php

namespace App\Http\Resources\Invoice;

use App\Models\Invoice\InvoiceLog;
use App\Traits\ApiCollectionResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/** @mixin InvoiceLog */
class InvoiceLogResource extends JsonResource
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
            'invoice_id' => $this->invoice_id,
            'message' => $this->message[app()->getLocale()],
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];

        if ($this->relationLoaded('invoice')) {
            $structure['invoice'] = new InvoiceResource($this->invoice);
        }

        return $structure;
    }
}
