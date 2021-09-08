<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

use App\Enums\Car\CarStatus;

use App\Traits\ApiCollectionResource;

class CarResource extends JsonResource
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
            'brand' => $this->brand,
            'model' => $this->model,
            'year' => $this->year,
            'car_name' => $this->car_name,
            'car_license' => $this->car_license,
            'insured' => $this->insured,
            'status' => $this->status,
            'status_description' => $this->status_description,
            'car_image_url' => $this->car_image_url,
        ];

        if ($this->relationLoaded('company')) {
            $structure['company'] = new CompanyResource($this->company);
        }

        if ($this->relationLoaded('worklists')) {
            $structure['worklists'] = WorklistResource::collection($this->worklists);
        }

        return $structure;
    }
}
