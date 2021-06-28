<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

use App\Traits\ApiCollectionResource;

class CompanyResource extends JsonResource
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
            'company_name' => $this->company_name,
            'email' => $this->email,
            'phone_number' => $this->phone_number,
            'vat_number' => $this->vat_number,
            'commerce_chamber_number' => $this->commerce_chamber_number,
            'company_logo_url' => $this->company_logo_url,
            'company_website_url' => $this->company_website_url,

            'visiting_address' => $this->visiting_address,
            'invoicing_address' => $this->invoicing_address,
        ];

        return $structure;
    }
}
