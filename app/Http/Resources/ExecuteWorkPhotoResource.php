<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ExecuteWorkPhotoResource extends JsonResource
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
            'execute_work_id' => $this->execute_work_id,
            'photo_condition_type' => $this->photo_condition_type,
            'photo_condition_type_description' => $this->photo_condition_type_description,
            'photo_url' => $this->photo_url,
            'photo_description' => $this->photo_description,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
