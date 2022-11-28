<?php

namespace App\Http\Resources\Appointment;

use App\Http\Resources\Employee\EmployeeResource;
use App\Http\Resources\Owner\OwnerResource;
use App\Traits\ApiCollectionResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class AppointmentEmployeeResource extends JsonResource
{
    use ApiCollectionResource;

    /**
     * Transform the resource into an array.
     *
     * @param Request $request
     * @return array
     */
    public function toArray($request): array
    {
        $structure = [
            'id' => $this->id,
            'appointment_id' => $this->appointment_id,
            'user_id' => $this->user_id,
        ];

        if ($this->relationLoaded('appointment')) {
            $structure['appointment'] = $this->appointment;
        }

        if ($this->relationLoaded('user')) {
            $user = [
                'role' => $this->user->user_role,
                'userable' => $this->user->user_role == "owner" ? new OwnerResource($this->user->owner) : new EmployeeResource($this->user->employee->load('user'))
            ];
            $structure['user'] = $user;
        }

        return $structure;
    }
}
