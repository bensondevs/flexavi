<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class OwnerResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'user' => $this->user,
            'is_prime_owner' => $this->is_prime_owner,
            'bank_name' => $this->bank_name,
            'bic_code' => $this->bic_code,
            'bank_account' => $this->bank_account,
            'bank_holder_name' => $this->bank_holder_name,
        ];
    }
}
