<?php

namespace App\Http\Resources\WorkContract\WorkContractFormatting;

use App\Http\Resources\WorkService\WorkServiceResource;
use App\Models\WorkContract\WorkContractService;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/** @mixin WorkContractService */
class WorkContractServiceResource extends JsonResource
{
    /**
     * @param Request $request
     * @return array
     */
    public function toArray($request): array
    {
        $structure = [
            'id' => $this->id,
            'amount' => $this->amount,
            'unit_price' => $this->unit_price,
            'formatted_unit_price' => $this->formatted_unit_price,
            'formatted_total' => $this->formatted_total,
            'total' => $this->total,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,

            'work_contract_id' => $this->work_contract_id,
            'work_service_id' => $this->work_service_id,
        ];

        if ($this->relationLoaded('workService')) {
            $structure['workService'] = new WorkServiceResource($this->workService);
        }

        return $structure;

    }
}
