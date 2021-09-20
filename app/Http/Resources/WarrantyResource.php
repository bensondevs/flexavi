<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

use App\Traits\ApiCollectionResource;

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
            'company_id' => $this->company_id,
            'appointment_id' => $this->appointment_id,
            'work_id' => $this->work_id,

            'status' => $this->status,
            'status_description' => $this->status_description,

            'problem_description' => $this->problem_description,
            'fixing_description' => $this->fixing_description,
            'internal_note' => $this->internal_note,
            'customer_note' => $this->customer_note,

            'amount' => $this->amount,
            'formatted_amount' => $this->formatted_amount,
            
            'paid_amount' => $this->paid_amount,
            'formatted_paid_amount' => $this->formatted_paid_amount,

            'unpaid_amount' => $this->unpaid_amount,
            'formatted_unpaid_amount' => $this->formatted_unpaid_amount,
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

        if ($this->relationLoaded('work')) {
            $work = new WorkResource($this->work);
            $structure['work'] = $work;
        }

        return $structure;
    }
}
