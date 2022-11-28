<?php

namespace App\Http\Resources\Workday;

use App\Http\Resources\Employee\EmployeeResource;
use App\Http\Resources\Owner\OwnerResource;
use Illuminate\Http\Resources\Json\JsonResource;

class WorkdayEmployeeResource extends JsonResource
{
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
            'fullname' => $this->fullname,
            'birth_date' => $this->birth_date,
            'phone' => $this->phone,
            'phone_verified_status' => $this->phone_verified_status,
            'email' => $this->email,
            'email_verified_status' => $this->email_verified_status,
            'profile_picture' => $this->profile_picture_url,
            'role' => $this->user_role,
            'userable' => $this->user_role == "owner" ? new OwnerResource($this->owner) : new EmployeeResource($this->employee)
        ];

        return $structure;
    }
}
