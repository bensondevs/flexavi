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
        return [
            'id' => $this->id,
            'fullname' => $this->fullname,
            'salutation' => $this->salutation,
            'birth_date' => $this->birth_date,
            'id_card_type' => $this->id_card_type,
            'id_card_number' => $this->id_card_number,
            'phone' => $this->phone,
            'email' => $this->email,
            'profile_picture' => $this->profile_picture_url,
        ];
    }
}
