<?php

namespace App\Http\Resources\Invitation;

use App\Models\Invitation\RegisterInvitation;
use App\Traits\ApiCollectionResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @mixin RegisterInvitation
 */
class RegisterInvitationResource extends JsonResource
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
            'date' => $this->created_at,
            'status' => $this->status,
            'invitationable' => $this->invitationable
        ];
    }
}
