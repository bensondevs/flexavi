<?php

namespace App\Http\Resources\Employee;

use App\Http\Resources\Address\AddressResource;
use App\Http\Resources\Appointment\AppointmentResource;
use App\Http\Resources\Company\CompanyResource;
use App\Http\Resources\Users\UserResource;
use App\Models\User\User;
use App\Traits\ApiCollectionResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class EmployeeResource extends JsonResource
{
    use ApiCollectionResource;

    /**
     * Transform the resource into an array.
     *
     * @param  Request  $request
     * @return array
     */
    public function toArray($request): array
    {
        $structure = [
            'id' => $this->id,
            'title' => $this->title,
            'employee_type' => $this->employee_type,
            'employee_type_description' => $this->employee_type_description,
            'employment_status' => $this->employment_status,
            'employment_status_description' => $this->employment_status_description,
            'contract_file_url' => $this->contract_file_url,
            'deleted_at' => $this->deleted_at,

            /**
             * @note Will be used later.
             */
            /*'average_worklists_costs' => $this->average_worklists_costs,
            'average_worklists_revenues' => $this->average_worklists_revenues,
            'average_worklists_profits' => $this->average_worklists_profits,*/
        ];

        if ($this->relationLoaded('user')) {
            $structure['user'] = new UserResource($this->user);
            if (!$this->user) {
                $structure['user'] = new UserResource(User::find($this->user_id));
            }
        }

        if ($this->relationLoaded('address')) {
            $structure['address'] = new AddressResource($this->address);
        }

        if ($this->relationLoaded('company')) {
            $structure['company'] = new CompanyResource($this->company);
        }

        /**
         * @note Will be used later
         */
        /*if ($this->relationLoaded('todayInspections')) {
            $structure['today_inspections'] = ($this->todayInspections);
        }

        if ($this->relationLoaded('appointments')) {
            $structure['appointments'] = AppointmentResource::collection($this->appointments);
        }

        if ($this->today_appointments_count !== null) {
            $structure['today_appointments_count'] = $this->today_appointments_count;
        }

        if ($this->inspections_count !== null) {
            $structure['inspections_count'] = $this->inspections_count;
        }*/

        return $structure;
    }
}
