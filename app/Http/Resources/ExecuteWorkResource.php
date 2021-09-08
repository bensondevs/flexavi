<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

use App\Traits\ApiCollectionResource;

use App\Enums\ExecuteWork\ExecuteWorkStatus;

class ExecuteWorkResource extends JsonResource
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
            'appointment_id' => $this->appointment_id,
            'work_id' => $this->work_id,
            'is_finished' => $this->is_finished,
            'is_continuation' => $this->is_continuation,

            'status' => $this->status,
            'status_description' => $this->status_description,

            'previous_execute_work_id' => $this->previous_execute_work_id,
            'note' => $this->note,
        ];

        if ($this->relationLoaded('company')) {
            $structure['company'] = $this->company;
        }

        if ($this->relationLoaded('appointment')) {
            $structure['appointment'] = $this->appointment;
        }

        if ($this->relationLoaded('work')) {
            $structure['work'] = $this->work;
        }

        if ($this->status == ExecuteWorkStatus::Finished) {
            $structure['finish_note'] = $this->finish_note;
        }

        return $structure;
    }
}
