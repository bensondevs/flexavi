<?php

namespace App\Http\Resources\Worklist;

use App\Enums\Worklist\WorklistStatus;
use App\Http\Resources\Appointment\AppointmentResource;
use App\Http\Resources\Car\CarResource;
use App\Http\Resources\Company\CompanyResource;
use App\Http\Resources\Cost\CostResource;
use App\Http\Resources\Employee\EmployeeResource;
use App\Http\Resources\Owner\OwnerResource;
use App\Http\Resources\Workday\WorkdayResource;
use App\Models\User\User;
use App\Traits\ApiCollectionResource;
use Illuminate\Http\Resources\Json\JsonResource;

class WorklistResource extends JsonResource
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
            'workday_id' => $this->workday_id,
            'user_id' => $this->user_id,
            'worklist_name' => $this->worklist_name,
            'status' => $this->status,
            'status_description' => $this->status_description,
            'sorting_route_status' => $this->sorting_route_status,
            'sorting_route_status_description' => $this->sorting_route_status_description,
            'always_sorting_route_status' => $this->always_sorting_route_status,
            'always_sorting_route_status_description' => $this->always_sorting_route_status_description,
        ];

        if ($this->relationLoaded('company')) {
            $structure['company'] = new CompanyResource($this->company);
        }

        if ($this->relationLoaded('workday')) {
            $structure['workday'] = new WorkdayResource($this->workday);
        }

        if ($this->relationLoaded('appointments')) {
            $structure['appointments'] = AppointmentResource::collection($this->appointments);
        }

        if ($this->appointments_count !== null) {
            $structure['appointments_count'] = $this->appointments_count;
        }

        if ($this->employees_count !== null) {
            $structure['employees_count'] = $this->employees_count;
        }

        if ($this->worklist_car_count !== null) {
            $structure['car_count'] = $this->worklist_car_count;
        }

        if ($this->customers_count !== null) {
            $structure['customers_count'] = $this->customers_count;
        }

        if ($this->relationLoaded('car')) {
            $structure['car'] = new CarResource($this->car);
        }

        if ($this->relationLoaded('appointEmployees')) {
            $structure['appoint_employees'] = ($this->appointEmployees);
        }

        if ($this->relationLoaded('employees')) {
            $structure['employees'] = WorklistEmployeeResource::collection($this->employees);
        }

        if ($this->relationLoaded('costs')) {
            $structure['costs'] = CostResource::collection($this->costs);
        }

        if ($this->relationLoaded('user')) {
            $user = $this->user;
            if (is_null($user)) $user = User::find($this->user_id);

            if (is_null($user)) {
                $structure['user'] = null;
            } else {
                $user = [
                    'role' => $user->user_role,
                    'userable' => $user->user_role == "owner" ? new OwnerResource($user->owner) : new EmployeeResource($user->employee->load('user'))
                ];
                $structure['user'] = $user;
            }
        }

        $structure['created_at'] = $this->created_at;
        $structure['updated_at'] = $this->updated_at;

        if ($this->status >= WorklistStatus::Processed) {
            $structure['processed_at'] = $this->processed_at;
        }

        if ($this->status >= WorklistStatus::Calculated) {
            $structure['calculated_at'] = $this->calculated_at;
        }

        return $structure;
    }
}
