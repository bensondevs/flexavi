<?php

namespace App\Http\Resources\WorkContract\WorkContractFormatting;

use App\Models\WorkContract\WorkContractContent;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/** @mixin WorkContractContent */
class WorkContractContentResource extends JsonResource
{
    /**
     * @param Request $request
     * @return array
     */
    public function toArray($request): array
    {
        return [
            'id' => $this->id,
            'order_index' => $this->order_index,
            'position_type' => $this->position_type,
            'text_type' => $this->text_type,
            'text' => $this->formatted_text,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,

            'work_contract_id' => $this->work_contract_id,

            'workContract' => new WorkContractResource($this->whenLoaded('workContract')),
        ];
    }
}
