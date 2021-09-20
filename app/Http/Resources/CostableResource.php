<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

use App\Models\Workday;
use App\Models\Worklist;
use App\Models\Appointment;

class CostableResource extends JsonResource
{
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
            'costable_id' => $this->costable_id,
            'costable_type' => $this->costable_type,
        ];

        if ($this->relationLoaded('cost')) {
            $structure['cost'] = new CostResource($this->cost);
        }

        if ($this->relationLoaded('costable')) {
            $costable = $this->costable;

            if ($this->costable_type == Workday::class) {
                $costable = new WorkdayResource($costable);
            } else if ($this->costable_type == Worklist::class) {
                $costable = new WorklistResource($costable);
            } else if ($this->costable_type == Appointment::class) {
                $costable = new AppointmentResource($costable)
            }

            $structure['costable'] = $costable;
        }

        return $structure;
    }
}
