<?php

namespace App\Http\Resources\WorkService;

use App\Http\Resources\Company\CompanyResource;
use App\Models\WorkService\WorkService;
use App\Traits\ApiCollectionResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @mixin WorkService
 */
class WorkServiceResource extends JsonResource
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
            'name' => $this->name,
            'price' => $this->price,
            'formatted_price' => $this->formatted_price,
            'tax_percentage' => $this->tax_percentage,
            'formatted_tax_percentage' => $this->formatted_tax_percentage,
            'total_price' => $this->total_price,
            'formatted_total_price' => $this->formatted_total_price,
            'unit' => $this->unit,
            'description' => $this->description,
            'status' => $this->status,
            'status_description' => $this->status_description,
        ];

        if ($this->relationLoaded('company')) {
            $structure['company'] = new CompanyResource($this->company);
        }

        return $structure;
    }
}
