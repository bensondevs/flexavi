<?php

namespace App\Http\Resources\Quotation;

use App\Models\Quotation\QuotationLog;
use App\Traits\ApiCollectionResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/** @mixin QuotationLog */
class QuotationLogResource extends JsonResource
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
            'quotation_id' => $this->quotation_id,
            'message' => $this->message,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];

        if ($this->relationLoaded('quotation')) {
            $structure['quotation'] = new QuotationResource($this->quotation);
        }
        return $structure;
    }
}
