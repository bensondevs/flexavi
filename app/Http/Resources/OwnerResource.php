<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

use App\Traits\ApiCollectionResource;

class OwnerResource extends JsonResource
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
            'user' => $this->user,
            'is_prime_owner' => $this->is_prime_owner,
            'bank_name' => $this->bank_name,
            'bic_code' => $this->bic_code,
            'bank_account' => $this->bank_account,
            'bank_holder_name' => $this->bank_holder_name,
            'address' => $this->address,
            'house_number' => $this->house_number,
            'house_number_suffix' => $this->house_number_suffix,
            'zipcode' => $this->zipcode,
            'city' => $this->city,
            'province' => $this->province,
        ];
    }
}
