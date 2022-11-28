<?php

namespace App\Http\Resources\Quotation;

use App\Http\Resources\WorkService\WorkServiceResource;
use App\Models\Quotation\QuotationItem;
use App\Models\WorkService\WorkService;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/** @mixin QuotationItem */
class QuotationItemResource extends JsonResource
{
    /**
     * @param Request $request
     * @return array
     */
    public function toArray($request): array
    {
        $structure = [
            'id' => $this->id,
            'quotation_id' => $this->quotation_id,
            'work_service_id' => $this->work_service_id,
            'unit_price' => $this->unit_price,
            'formatted_unit_price' => $this->formatted_unit_price,
            'amount' => $this->amount,
            'total' => $this->total,
            'formatted_total' => $this->formatted_total,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];

        if ($this->relationLoaded('workService')) {
            $workService = $this->workService;
            if (!$workService) {
                $workService = WorkService::find($this->work_service_id);
            }
            $structure['work_service'] = new WorkServiceResource($workService);
        }

        if ($this->relationLoaded('quotation')) {
            $structure['quotation'] = new QuotationResource($this->quotation);
        }
        return $structure;
    }
}
