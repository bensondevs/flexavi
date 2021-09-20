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
        $structure = [
            'id' => $this->id,

            'fullname' => $this->fullname,
            'email' => $this->email,
            'phone' => $this->phone,
            'second_phone' => $this->second_phone,

            'address' => $this->address,
            'house_number' => $this->house_number,
            'house_number_suffix' => $this->house_number_suffix,
            'zipcode' => $this->zipcode,
            'city' => $this->city,
            'province' => $this->province,
        ];

        if ($this->relationLoaded('company')) {
            $structure['company'] = new CompanyResource($this->company);
        }

        return $structure;
    }
}