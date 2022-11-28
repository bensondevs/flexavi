<?php

namespace App\Http\Resources\Receipt;

use App\Traits\ApiCollectionResource;
use Illuminate\Http\Resources\Json\JsonResource;

class ReceiptResource extends JsonResource
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
            'receiptable_type' => $this->receiptable_type,
            'receiptable_id' => $this->receiptable_id,
            'description' => $this->description,
            'receipt_url' => $this->receipt_url,
        ];

        if ($this->relationLoaded('receiptable')) {
            $receiptable = $this->receiptable;
            $structure[get_lower_class($receiptable)] = $receiptable;
        }

        return $structure;
    }
}
