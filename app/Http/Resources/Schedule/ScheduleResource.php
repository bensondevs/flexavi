<?php

namespace App\Http\Resources\Schedule;

use Illuminate\Http\Resources\Json\JsonResource;

class ScheduleResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'activity_name' => $this->activity_name,
            'start' => carbon($this->start)->format('M d, Y; H:i:s'),
            'end' => carbon($this->end)->format('M d, Y; H:i:s'),
            'include_weekend' => (bool) $this->include_weekend,
            'start_money' => number_format($this->start_money, 2),
        ];
    }
}
