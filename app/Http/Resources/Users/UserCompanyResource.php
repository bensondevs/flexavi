<?php

namespace App\Http\Resources\Users;

use Illuminate\Http\Resources\Json\JsonResource;

class UserCompanyResource extends JsonResource
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
            'id' => $this->id,

            'visiting_address' => $this->visiting_address,
            'invoicing_address' => $this->invoicing_address,

            'email' => $this->email,
            'phone_number' => $this->phone_number,
            'vat_number' => $this->vat_number,
            'commerce_chamber_number' => $this->commerce_chamber_number,
            'company_logo' => asset($this->company_logo_url),
            'company_website_url' => $this->company_website_url,
        ];
    }
}
