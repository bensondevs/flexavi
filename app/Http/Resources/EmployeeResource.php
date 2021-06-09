<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

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
            'employment_status' => $this->employment_status,
            'employment_status_label' => $this->employment_status_label,
            'user' => $this->user,
            'address' => $this->address,
            'house_number' => $this->house_number,
            'house_number_suffix' => $this->house_number_suffix,
            'zipcode' => $this->zipcode,
            'city' => $this->city,
            'province' => $this->province,
        ];
    }
}
