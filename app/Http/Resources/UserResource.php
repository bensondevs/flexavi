<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

use App\Traits\ApiCollectionResource;

class UserResource extends JsonResource
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
            'fullname' => $this->fullname,
            'birth_date' => $this->birth_date,
            'id_card_type' => $this->id_card_type,
            'id_card_number' => $this->id_card_number,
            'phone' => $this->phone,
            'phone_verified_status' => $this->phone_verified_status,
            'email' => $this->email,
            'email_verified_status' => $this->email_verified_status,
            'profile_picture' => $this->profile_picture_url,
            'role' => $this->user_role,
        ];

        if ($this->token) {
            $structure['token'] = $this->token;
        }

        return $structure;
    }
}
