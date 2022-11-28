<?php

namespace App\Http\Resources\Employee;

use App\Traits\ApiCollectionResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class EmployeeInvitationResource extends JsonResource
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
        return [
            'id' => $this->id,
            'registration_code' => $this->registration_code,
            'invited_email' => $this->invited_email,
            'invitation_date' => $this->created_at->format('Y-m-d H:i:s'),
            'name' => $this->name,
            'birth_date' => $this->birth_date,
            'phone' => $this->phone,
            'expiry_time' => $this->expiry_time,
            'role' => $this->role,
            'role_description' => $this->role_description,
            'status' => $this->status,
            'status_description' => $this->status_description,
            'contract_file_url' => $this->contract_file_url,
            'permissions' => $this->permissions,
        ];
    }
}
