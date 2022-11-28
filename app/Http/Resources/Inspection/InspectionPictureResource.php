<?php

namespace App\Http\Resources\Inspection;

use App\Http\Resources\Work\WorkResource;
use App\Traits\ApiCollectionResource;
use Illuminate\Http\Resources\Json\JsonResource;

class InspectionPictureResource extends JsonResource
{
    use ApiCollectionResource;

    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        $pictures = [];
        foreach ($this->getMedia('inspection_pictures') as $media) {
            $pictures[] = $media->getFullUrl();
        }

        $structure =  [
            'id' => $this->id,
            'inspection_id' => $this->inspection_id,
            'name' => $this->name,
            'width' => $this->width,
            'length' => $this->length,
            'amount' => $this->amount,
            'note' => $this->note,
            'photos' => $pictures
        ];

        if ($this->relationLoaded('works')) {
            $structure['works'] = WorkResource::collection($this->works);
        }

        return $structure;
    }
}
