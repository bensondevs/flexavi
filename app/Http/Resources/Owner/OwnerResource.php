<?php

namespace App\Http\Resources\Owner;

use App\Http\Resources\Address\AddressResource;
use App\Http\Resources\Company\CompanyResource;
use App\Http\Resources\Users\UserResource;
use App\Models\Owner\Owner;
use App\Traits\ApiCollectionResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @mixin Owner
 */
class OwnerResource extends JsonResource
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
            'user_id' => $this->user_id,
            'is_prime_owner' => $this->is_prime_owner,
            'deleted_at' => $this->deleted_at,
        ];

        if ($this->relationLoaded('address')) {
            $structure['address'] = new AddressResource($this->address);
        }

        if ($this->relationLoaded('company')) {
            $structure['company'] = new CompanyResource($this->company);
        }

        if ($this->relationLoaded('user')) {
            $structure['user'] = new UserResource($this->user);
        }

        return $structure;
    }
}
