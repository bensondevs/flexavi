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
        $structure = [
            'id' => $this->id,
            'title' => $this->title,
            'employee_type' => $this->employee_type,
            'employee_type_description' => $this->employee_type_description,
            'employment_status' => $this->employment_status,
            'employment_status_description' => $this->employment_status_description,
        ];

        if ($this->relationLoaded('user')) {
            $structure['user'] = new UserResource($this->user);
        }

        if ($this->relationLoaded('addresses')) {
            $structure['addresses'] = AddressResource::collection($this->addresses);
        }

        if ($this->relationLoaded('company')) {
            $structure['company'] = new CompanyResource($this->company);
        }

        if ($this->relationLoaded('todayInspections')) {
            $structure['today_inspections'] = ($this->todayInspections);
        }

        if ($this->inspections_count !== null) {
            $structure['inspections_count'] = $this->inspections_count;
        }

        return $structure;
    }
}