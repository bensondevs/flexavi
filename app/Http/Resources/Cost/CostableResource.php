<?php

namespace App\Http\Resources\Cost;

use App\Traits\ApiCollectionResource;
use Illuminate\Http\Resources\Json\JsonResource;

class CostableResource extends JsonResource
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
            'costable_id' => $this->costable_id,
            'costable_type' => $this->costable_type,
        ];

        if ($this->relationLoaded('cost')) {
            $structure['cost'] = new CostResource($this->cost);
        }

        if ($this->relationLoaded('costable')) {
            if ($costable = $this->costable) {
                $pureClass = get_pure_class($costable);
                $className = get_lower_class($costable);
                $resourceClass = '\\App\Http\\Resources\\' . $pureClass . 'Resource';

                $structure[$className] = new $resourceClass($costable);
            }
        }

        return $structure;
    }
}
