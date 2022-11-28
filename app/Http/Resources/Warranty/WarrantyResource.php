<?php

namespace App\Http\Resources\Warranty;

use App\Http\Resources\Appointment\AppointmentResource;
use App\Http\Resources\Company\CompanyResource;
use App\Traits\ApiCollectionResource;
use Illuminate\Http\Resources\Json\JsonResource;

class WarrantyResource extends JsonResource
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
            'company_id' => $this->company_id,
            'appointment_id' => $this->appointment_id,
            'customer_name' => $this->appointment->customer ? $this->appointment->customer->fullname : null,
            'status' => $this->status,
            'status_description' => $this->status_description,
            'total_price' => $this->total_price,
            'formatted_total_price' => $this->formatted_total_price,
            'total_company_paid' => $this->total_company_paid,
            'formatted_total_company_paid' => $this->formatted_total_company_paid,
            'total_customer_paid' => $this->total_customer_paid,
            'formatted_total_customer_paid' => $this->formatted_total_customer_paid,
        ];

        if ($this->relationLoaded('company')) {
            $company = new CompanyResource($this->company);
            $structure['company'] = $company;
        }

        if ($this->relationLoaded('appointment')) {
            $appointment = $this->appointment;
            $appointment = new AppointmentResource($appointment);
            $structure['appointment'] = $appointment;
        }

        if ($this->relationLoaded('warrantyAppointments')) {
            $structure['warrantyAppointments'] = WarrantyAppointmentResource::collection($this->warrantyAppointments);
        }

        return $structure;
    }
}
