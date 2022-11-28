<?php

namespace App\Http\Resources\Company;

use App\Enums\Address\AddressType;
use App\Http\Resources\Address\AddressResource;
use App\Models\Address\Address;
use App\Models\Company\Company;
use App\Traits\ApiCollectionResource;
use App\Traits\ModelExtension;
use Illuminate\Http\Resources\Json\JsonResource;

class CompanyResource extends JsonResource
{
    use ApiCollectionResource;
    use ModelExtension;

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
            'company_logo_url' => $this->logo_url,
            'company_website_url' => $this->company_website_url,
        ];

        $structure['visiting_address'] = new AddressResource($this->visiting_address);
        if (!$this->visiting_address) {
            $structure['visiting_address'] = new AddressResource(
                Address::where('addressable_type', get_class(new Company()))
                    ->where('addressable_id', $this->id)
                    ->where('address_type', AddressType::VisitingAddress)
                    ->first()
            );
        }

        $structure['invoicing_address'] = new AddressResource($this->invoicingAddress);
        if (!$this->invoicing_address) {
            $structure['invoicing_address'] = new AddressResource(
                Address::where('addressable_type', get_class(new Company()))
                    ->where('addressable_id', $this->id)
                    ->where('address_type', AddressType::InvoicingAddress)
                    ->first()
            );
        }

        return $structure;
    }
}
