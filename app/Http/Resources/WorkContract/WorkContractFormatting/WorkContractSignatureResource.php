<?php

namespace App\Http\Resources\WorkContract\WorkContractFormatting;

use App\Models\WorkContract\WorkContractSignature;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/** @mixin WorkContractSignature */
class WorkContractSignatureResource extends JsonResource
{
    /**
     * @param Request $request
     * @return array
     */
    public function toArray($request): array
    {
        return [
            'id' => $this->id,
            'work_contract_id' => $this->work_contract_id,
            'name' => $this->name,
            'type' => $this->type,
            'type_description' => $this->type_description,
            'signature_url' => $this->signature_url,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
