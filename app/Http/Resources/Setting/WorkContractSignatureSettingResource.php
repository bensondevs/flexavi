<?php

namespace App\Http\Resources\Setting;

use App\Models\Setting\WorkContractSignatureSetting;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/** @mixin WorkContractSignatureSetting */
class WorkContractSignatureSettingResource extends JsonResource
{
    /**
     * @param Request $request
     * @return array
     */
    public function toArray($request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'type' => $this->type,
            'type_description' => $this->type_description,
            'signature_url' => $this->signature_url,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
