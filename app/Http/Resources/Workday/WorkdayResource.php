<?php

namespace App\Http\Resources\Workday;

use App\Enums\Workday\WorkdayStatus;
use App\Http\Resources\Appointment\AppointmentResource;
use App\Http\Resources\Appointment\SubAppointmentResource;
use App\Http\Resources\Car\CarResource;
use App\Http\Resources\Company\CompanyResource;
use App\Http\Resources\Customer\CustomerResource;
use App\Http\Resources\Worklist\WorklistResource;
use App\Traits\ApiCollectionResource;
use Illuminate\Http\Resources\Json\JsonResource;

class WorkdayResource extends JsonResource
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
            'date' => $this->date,
            'status' => $this->status,
            'status_description' => $this->status_description,
        ];

        if ($this->relationLoaded('company')) {
            $structure['company'] = new CompanyResource($this->company);
        }

        if ($this->relationLoaded('worklists')) {
            $structure['worklists'] = WorklistResource::collection($this->worklists);
        }

        if ($this->relationLoaded('appointments')) {
            $structure['appointments'] = AppointmentResource::collection($this->appointments);
        }

        if ($this->relationLoaded('unplannedAppointments')) {
            $structure['unplanned_appointments'] = AppointmentResource::collection($this->unplannedAppointments);
        }

        if ($this->relationLoaded('subAppointments')) {
            $structure['sub_appointments'] = SubAppointmentResource::collection($this->subAppointments);
        }

        if ($this->relationLoaded('employees')) {
            $structure['employees'] = WorkdayEmployeeResource::collection($this->employees);
        }

        if ($this->relationLoaded('customers')) {
            $structure['customers'] = CustomerResource::collection($this->customers);
        }

        if ($this->relationLoaded('cars')) {
            $structure['cars'] = CarResource::collection($this->cars);
        }

        if ($this->status >= WorkdayStatus::Processed) {
            $structure['processed_at'] = $this->processed_at;
        }

        if ($this->status >= WorkdayStatus::Calculated) {
            $structure['calculated_at'] = $this->calculated_at;
        }

        if ($this->worklists_count !== null) {
            $structure['worklists_count'] = $this->worklists_count;
        }

        if ($this->appointments_count !== null) {
            $structure['appointments_count'] = $this->appointments_count;
        }

        if (request('with_cars_count')) $structure['cars_count'] = count($this->cars);

        if (request('with_employees_count')) $structure['employees_count'] = count($this->employees);

        if (request('with_customers_count')) $structure['customers_count'] = count($this->customers);

        if (request('with_subAppointments_count')) $structure['sub_appointments_count'] = count($this->subAppointments);

        if (request('with_appointments_count')) $structure['appointments_count'] = count($this->appointments);


        if ($this->unplanned_appointments_count !== null) {
            $structure['unplanned_appointments_count'] = count($this->unplannedAppointments);
        }

        return $structure;
    }
}
