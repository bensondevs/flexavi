<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

use App\Traits\ApiCollectionResource;

class CustomerResource extends JsonResource
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
            'address' => $this->address,
            'house_number' => $this->house_number,
            'zipcode' => $this->zipcode,
            'city' => $this->city,
            'province' => $this->province,
            'email' => $this->email,
            'phone' => $this->phone,
            'second_phone' => $this->second_phone,
        ];
    }
}