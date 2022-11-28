<?php

namespace App\Http\Resources\Owner;

use App\Models\Owner\OwnerInvitation;
use App\Traits\ApiCollectionResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @mixin OwnerInvitation
 */
class OwnerInvitationResource extends JsonResource
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
        return [
            'id' => $this->id,
            'registration_code' => $this->registration_code,
            'invited_email' => $this->invited_email,
            'invitation_date' => carbon($this->created_at)->format('Y-m-d H:i:s'),
            'name' => $this->name,
            'phone' => $this->phone,
            'expiry_time' => $this->expiry_time,
            'permissions' => $this->permissions,
            'status' => $this->status,
            'status_description' => $this->status_description,
            'deleted_at' => $this->deleted_at
        ];
    }
}
