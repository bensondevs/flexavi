<?php

namespace App\Http\Resources\Setting;

use App\Models\Setting\WorkContractContentSetting;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/** @mixin WorkContractContentSetting */
class WorkContractContentSettingResource extends JsonResource
{
    /**
     * @param Request $request
     * @return array
     */
    public function toArray($request): array
    {
        return [
            'id' => $this->id,
            'work_contract_setting_id' => $this->work_contract_setting_id,
            'order_index' => $this->order_index,
            'position_type' => $this->position_type,
            'position_type_description' => $this->position_type_description,
            'text_type' => $this->text_type,
            'text_type_description' => $this->text_type_description,
            'text' => $this->text,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
