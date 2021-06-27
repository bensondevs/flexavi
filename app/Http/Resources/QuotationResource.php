<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

use App\Http\Resources\CustomerResource;
use App\Http\Resources\AppointmentResource;

use App\Traits\ApiCollectionResource;

use App\Enums\Quotation\QuotationStatus;

class QuotationResource extends JsonResource
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
            'customer' => new CustomerResource($this->customer),
            'appointment' => new AppointmentResource($this->appointment),

            'type' => $this->type,
            'type_description' => $this->type_description,

            'quotation_date' => $this->quotation_date,
            'quotation_number' => $this->quotation_number,

            'contact_person' => $this->contact_person,
            'address' => $this->address,
            'zip_code' => $this->zip_code,
            'address' => $this->address,
            'phone_number' => $this->phone_number,
            'quotation_description' => $this->quotation_description,
            'quotation_document_url' => $this->quotation_document_url,

            'is_signed' => $this->is_signed,

            'amount' => $this->amount,
            'formatted_amount' => $this->formatted_amount,

            'vat_percentage' => $this->vat_percentage,
            'formatted_vat_percentage' => $this->formatted_vat_percentage,
            'vat_amount' => $this->vat_amount,
            'formatted_vat_amount' => $this->formatted_vat_amount,

            'discount_amount' => $this->discount_amount,
            'total_amount' => $this->total_amount,

            'expiry_date' => $this->expiry_date,
            'formatted_expiry_date' => $this->formatted_expiry_date,

            'status' => $this->status,
            'status_description' => $this->status_description,

            'payment_method' => $this->payment_method,
            'payment_method_description' => $this->payment_method_description,
        ];

        if ($this->status >= QuotationStatus::Draft) {
            $structure['created_at'] = $this->created_at;
        }

        if ($this->status >= QuotationStatus::Sent) {
            $structure['first_sent_at'] = $this->first_sent_at;
            $structure['last_sent_at'] = $this->last_sent_at;
        }

        if ($this->status >= QuotationStatus::Revised) {
            $structure['revised_at'] = $this->revised_at;
        }

        if ($this->status >= QuotationStatus::Honored) {
            $structure['honor_note'] = $this->honor_note;
            $structure['honored_at'] = $this->honored_at;
        }

        if ($this->status >= QuotationStatus::Cancelled) {
            $structure['canceller'] = $this->canceller;
            $structure['canceller_description'] = $this->canceller_description;
            $structure['cancellation_reason'] = $this->cancellation_reason;
            $structure['cancelled_at'] = $this->cancelled_at;
        }

        return $structure;
    }
}
