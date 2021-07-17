<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

use App\Http\Resources\UserResource;

use App\Traits\ApiCollectionResource;

class EmployeeResource extends JsonResource
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
        return [
            'id' => $this->id,
            'title' => $this->title,
            'employee_type' => $this->employee_type,
            'employee_type_description' => $this->employee_type_description,
            'employment_status' => $this->employment_status,
            'employment_status_description' => $this->employment_status_description,
            'user' => new UserResource($this->user),
            'inspections_count' => $this->inspections_count,
        ];
    }
}