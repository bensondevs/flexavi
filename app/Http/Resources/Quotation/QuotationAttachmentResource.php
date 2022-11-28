<?php

namespace App\Http\Resources\Quotation;

use App\Traits\ApiCollectionResource;
use Illuminate\Http\Resources\Json\JsonResource;

class QuotationAttachmentResource extends JsonResource
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
            'name' => $this->name,
            'description' => $this->description,
            'attachment_url' => $this->attachment_url,
        ];
    }
}
