<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

use App\Traits\ApiCollectionResource;

class AddressResource extends JsonResource
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

            'address_type' => $this->address_type,
            'address_type_description' => $this->address_type_description,

            'address' => $this->address,
            'house_number' => $this->house_number,
            'house_number_suffix' => $this->house_number_suffix,
            'zipcode' => $this->zipcode,
            'city' => $this->city,
            'province' => $this->province,
        ];
    }
}
