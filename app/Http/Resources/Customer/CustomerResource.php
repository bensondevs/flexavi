<?php

namespace App\Http\Resources\Customer;

use App\Http\Resources\Address\AddressResource;
use App\Http\Resources\Appointment\AppointmentResource;
use App\Http\Resources\Company\CompanyResource;
use App\Http\Resources\Invoice\InvoiceResource;
use App\Http\Resources\Quotation\QuotationResource;
use App\Http\Resources\WorkContract\WorkContractResource;
use App\Models\Customer\Customer;
use App\Traits\ApiCollectionResource;
use App\Traits\ModelExtension;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @mixin Customer
 */
class CustomerResource extends JsonResource
{
    use ApiCollectionResource;
    use ModelExtension;

    /**
     * Transform the resource into an array.
     *
     * @param Request $request
     * @return array
     */
    public function toArray($request): array
    {
        $structure = [
            'id' => $this->id,
            'salutation' => $this->salutation,
            'salutation_description' => $this->salutation_description,
            'fullname' => $this->fullname,
            'email' => $this->email,
            'phone' => $this->phone,
            'second_phone' => $this->second_phone,
            'acquired_through' => $this->acquired_through,
            'acquired_through_description' => $this->acquired_through_description

        ];

        if ($this->relationLoaded('address')) {
            $address = new AddressResource($this->address);
            $structure['address'] = $address;
        }

        if ($this->relationLoaded('addresses')) {
            $addresses = AddressResource::collection($this->addresses);
            $structure['addresses'] = $addresses;
        }

        if ($this->relationLoaded('company')) {
            $company = new CompanyResource($this->company);
            $structure['company'] = $company;
        }

        if ($this->relationLoaded('quotations')) {
            $quotations = QuotationResource::collection($this->quotations);
            $structure['quotations'] = $quotations;
        }

        if ($this->relationLoaded('appointments')) {
            $appointments = AppointmentResource::collection($this->appointments);
            $structure['appointments'] = $appointments;
        }

        if ($this->relationLoaded('invoices')) {
            $invoices = InvoiceResource::collection($this->invoices);
            $structure['invoices'] = $invoices;
        }

        if ($this->relationLoaded('workContracts')) {
            $workContracts = WorkContractResource::collection($this->workContracts);
            $structure['work_contracts'] = $workContracts;
        }

        return $structure;
    }
}
