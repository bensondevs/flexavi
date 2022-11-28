<?php

namespace App\Http\Resources\Auth;

use App\Models\User\User;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @mixin User
 */
class ForgotPasswordResource extends JsonResource
{
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
            'fullname' => $this->fullname,
            'phone' => hidePartiallyPhone($this->phone),
            'email' => hidePartiallyEmail($this->email),
            'role' => $this->user_role,
            'profile_picture' => $this->profile_picture_url,
        ];
    }
}
