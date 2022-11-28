<?php

namespace App\Http\Resources\Invoice;

use App\Http\Resources\Customer\CustomerResource;
use App\Models\Invoice\Invoice;
use App\Traits\ApiCollectionResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @mixin Invoice
 */
class InvoiceResource extends JsonResource
{
    use ApiCollectionResource;

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

            'number' => $this->number,
            'company_id' => $this->company_id,
            'customer_id' => $this->customer_id,
            'customer_address' => $this->customer_address,

            'date' => $this->date,
            'formatted_date' => $this->formatted_date,

            'due_date' => $this->due_date,
            'formatted_due_date' => $this->formatted_due_date,

            'amount' => $this->amount,
            'formatted_amount' => $this->formatted_amount,

            'taxes' => $this->taxes,
            'total_taxes' => $this->total_taxes,
            'formatted_total_taxes' => $this->formatted_total_taxes,

            'potential_amount' => $this->potential_amount,
            'formatted_potential_amount' => $this->formatted_potential_amount,

            'discount_amount' => $this->discount_amount,
            'formatted_discount_amount' => $this->formatted_discount_amount,

            'total_amount' => $this->total_amount,
            'formatted_total_amount' => $this->formatted_total_amount,

            'status' => $this->status,
            'status_description' => $this->status_description,

            'payment_method' => $this->payment_method,
            'payment_method_description' => $this->payment_method_description,

            'note' => $this->note,
            'available_actions' => $this->available_actions,
        ];


        if ($this->relationLoaded('customer')) {
            $structure['customer'] = new CustomerResource($this->customer);;
        }

        if ($this->relationLoaded('items')) {
            $structure['items'] = InvoiceItemResource::collection($this->items);
        }

        if ($this->relationLoaded('reminder')) {
            $structure['reminder'] = new InvoiceReminderResource($this->reminder);
        }

        return $structure;
    }
}
