<?php

namespace App\Http\Resources\Revenue;

use App\Traits\ApiCollectionResource;
use Illuminate\Http\Resources\Json\JsonResource;

class RevenueResource extends JsonResource
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
        return parent::toArray($request);
    }
}
