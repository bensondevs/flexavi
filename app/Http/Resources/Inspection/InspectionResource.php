<?php

namespace App\Http\Resources\Inspection;

use App\Http\Resources\Appointment\AppointmentResource;
use App\Http\Resources\Company\CompanyResource;
use App\Http\Resources\Customer\CustomerResource;
use App\Traits\ApiCollectionResource;
use Illuminate\Http\Resources\Json\JsonResource;

class InspectionResource extends JsonResource
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
        $structure = [
            'id' => $this->id,
            'appointment_id' => $this->appointment_id,
            'company_id' => $this->company_id,
        ];

        if ($this->relationLoaded('company')) {
            $structure['company'] = new CompanyResource($this->company);
        }

        if ($this->relationLoaded('customer')) {
            $structure['customer'] = new CustomerResource($this->customer);
        }

        if ($this->relationLoaded('appointment')) {
            $structure['appointment'] = new AppointmentResource($this->appointment);
        }

        if ($this->relationLoaded('pictures')) {
            $structure['pictures'] =  InspectionPictureResource::collection($this->pictures);
        }



        return $structure;
    }
}
