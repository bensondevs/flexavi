<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

use App\Traits\ApiCollectionResource;

class AppointmentEmployeeResource extends JsonResource
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
            'appointment_id' => $this->appointment_id,
            'employee_id' => $this->employee_id,
        ];

        if ($this->relationLoaded('appointment')) {
            $structure['appointment'] = $this->appointment;
        }

        if ($this->relationLoaded('employee')) {
            $structure['employee'] = $this->employee;
        }

        return $structure;
    }
}
