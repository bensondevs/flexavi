<?php

namespace App\Http\Resources\Invoice;

use App\Models\Setting\InvoiceSetting;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/** @mixin InvoiceSetting */
class InvoiceSettingResource extends JsonResource
{
    /**
     * @param Request $request
     * @return array
     */
    public function toArray($request): array
    {
        return [
            'id' => $this->id,

            'auto_reminder_activated' => $this->auto_reminder_activated,
            'first_reminder_type' => $this->first_reminder_type,
            'first_reminder_type_description' => $this->first_reminder_type_description,
            'first_reminder_days' => $this->first_reminder_days,

            'second_reminder_type' => $this->second_reminder_type,
            'second_reminder_type_description' => $this->second_reminder_type_description,
            'second_reminder_days' => $this->second_reminder_days,

            'third_reminder_type' => $this->third_reminder_type,
            'third_reminder_type_description' => $this->third_reminder_type_description,
            'third_reminder_days' => $this->third_reminder_days,

            'debt_collector_reminder_type' => $this->debt_collector_reminder_type,
            'debt_collector_reminder_type_description' => $this->debt_collector_reminder_type_description,
            'debt_collector_reminder_days' => $this->debt_collector_reminder_days,
        ];
    }
}
