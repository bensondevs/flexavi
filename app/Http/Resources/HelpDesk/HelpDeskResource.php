<?php

namespace App\Http\Resources\HelpDesk;

use App\Http\Resources\Users\UserResource;
use App\Traits\ApiCollectionResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @mixin HelpDesk
 */
class HelpDeskResource extends JsonResource
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
            'title' => $this->title,
            'content' => $this->content,
        ];

        if ($this->relationLoaded('user')) {
            $structure['user'] = new UserResource($this->user);
        }

        return $structure;
    }
}
