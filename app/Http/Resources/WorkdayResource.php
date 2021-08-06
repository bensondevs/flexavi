<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

use App\Traits\ApiCollectionResource;

use App\Enums\Workday\WorkdayStatus;

class WorkdayResource extends JsonResource
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
            'date' => $this->date,
            'total_worklists' => $this->worklists_count,
            'total_appointments' => $this->appointments_count,
            'status' => $this->status,
            'status_description' => $this->status_description,
        ];

        if ($this->relationLoaded('company')) {
            $structure['company'] = new CompanyResource($this->company);
        }

        if ($this->relationLoaded('worklists')) {
            $structure['worklists'] = WorklistResource::collection($this->worklists);
        }

        if ($this->status >= WorkdayStatus::Processed) {
            $structure['processed_at'] = $this->processed_at;
        }

        if ($this->status >= WorkdayStatus::Calculated) {
            $structure['calculated_at'] = $this->calculated_at;
        }

        return $structure;
    }
}
