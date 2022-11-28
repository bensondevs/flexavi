<?php

namespace App\Http\Resources\ExecuteWork;

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
        $structure =  [
            'id' => $this->id,
            'execute_work_id' => $this->execute_work_id,
            'name' => $this->name,
            'length' => $this->length,
            'note' => $this->note,
            'photos' => $this->getPhotosUrl(),
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];

        if ($this->relationLoaded('works')) {
            $structure['works'] = WorkWarrantyResource::collection($this->works);
        }

        return $structure;
    }
}
