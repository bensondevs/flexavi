<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

use App\Traits\ApiCollectionResource;

use App\Enums\Worklist\WorklistStatus;

class WorklistResource extends JsonResource
{
    use ApiCollectionResource;

    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        $structure = [
            'id' => $this->id,
            'company_id' => $this->company_id,
            'workday_id' => $this->workday_id,
            'worklist_name' => $this->worklist_name,
            'status' => $this->status,
            'status_description' => $this->status_description,
        ];

        if ($this->relationLoaded('company')) {
            $structure['company'] = new CompanyResource($this->company);
        }

        if ($this->relationLoaded('workday')) {
            $structure['workday'] = new WorkdayResource($this->workday);
        }

        if ($this->appointments_count) {
            $structure['total_appointments'] = $this->appointments_count;
        }

        if ($this->relationLoaded('appointments')) {
            $structure['appointments'] = AppointmentResource::collection($this->appointments);
        }

        if ($this->relationLoaded('costs')) {
            $structure['costs'] = CostResource::collection($this->costs);
        }

        $structure['created_at'] = $this->created_at;
        $structure['updated_at'] = $this->updated_at;

        if ($this->status >= WorklistStatus::Processed) {
            $structure['processed_at'] = $this->processed_at;
        }

        if ($this->status >= WorklistStatus::Calculated) {
            $structure['calculated_at'] = $this->calculated_at;
        }

        return $structure;
    }
}
