<?php

namespace App\Http\Resources\Users;

use App\Models\User\User;
use App\Repositories\Permission\PermissionRepository;
use App\Traits\ApiCollectionResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @mixin User
 */
class UserResource extends JsonResource
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
            'fullname' => $this->fullname,
            'birth_date' => $this->birth_date,
            'phone' => $this->phone,
            'phone_verified_status' => $this->phone_verified_status,
            'email' => $this->email,
            'email_verified_status' => $this->email_verified_status,
            'profile_picture' => $this->profile_picture_url,
            'role' => $this->user_role,
        ];

        if (isset($this->token)) {
            $structure['token'] = $this->token;
        }

        if ($this->relationLoaded('permissions')) {
            $structure['permissions'] = app(PermissionRepository::class)
            ->userPermissions(User::findOrFail($this->id), true);
        }

        return $structure;
    }
}
