<?php

namespace App\Http\Resources\Setting;

use App\Models\Setting\WorkContractSetting;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/** @mixin WorkContractSetting */
class WorkContractSettingResource extends JsonResource
{
    /**
     * @param Request $request
     * @return array
     */
    public function toArray($request): array
    {
        $structure = [
            'id' => $this->id,
            'company_id' => $this->company_id,
            'footer' => $this->footer,
            'signature_url' => $this->signature_url,
            'signature_name' => $this->signature_name,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];

        if ($this->relationLoaded('forewordContents')) {
            $structure['foreword_contents'] = WorkContractContentSettingResource::collection($this->forewordContents);
        }

        if ($this->relationLoaded('contractContents')) {
            $structure['contract_contents'] = WorkContractContentSettingResource::collection($this->contractContents);
        }

        return $structure;
    }
}
