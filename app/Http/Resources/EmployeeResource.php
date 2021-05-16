<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class EmployeeResource extends JsonResource
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
            'title' => $this->title,
            'employee_type' => $this->employee_type,
            'employment_status' => $this->employment_status,
            'employment_status_label' => $this->employment_status_label,
            'user' => $this->user,
        ];
    }
}
