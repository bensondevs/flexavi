<?php

namespace App\Http\Resources\ExecuteWork;

use App\Enums\ExecuteWork\ExecuteWorkStatus;
use App\Traits\ApiCollectionResource;
use Illuminate\Http\Resources\Json\JsonResource;

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

            'status' => $this->status,
            'status_description' => $this->status_description,
            'note' => $this->note,
        ];

        if ($this->relationLoaded('company')) {
            $structure['company'] = $this->company;
        }

        if ($this->relationLoaded('appointment')) {
            $structure['appointment'] = $this->appointment;
        }

        if ($this->relationLoaded('relatedMaterial')) {
            $structure['related_material'] = new ExecuteWorkRelatedMaterialResource($this->relatedMaterial);
        }

        if ($this->relationLoaded('work')) {
            $structure['work'] = $this->work;
        }

        if ($this->relationLoaded('photos')) {
            $structure['photos'] = ExecuteWorkPhotoResource::collection($this->photos);
        }

        if ($this->status == ExecuteWorkStatus::Finished) {
            $structure['finish_note'] = $this->finish_note;
        }

        return $structure;
    }
}
