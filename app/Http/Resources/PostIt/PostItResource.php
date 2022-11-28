<?php

namespace App\Http\Resources\PostIt;

use App\Http\Resources\Users\UserResource;
use App\Traits\ApiCollectionResource;
use Illuminate\Http\Resources\Json\JsonResource;

class PostItResource extends JsonResource
{
    use ApiCollectionResource;

    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        $structure = [
            'id' => $this->id,
            'user_id' => $this->user_id,
            'content' => $this->content
        ];

        if ($this->relationLoaded('user')) {
            $user = $this->user;
            $structure['user'] = new UserResource($user);
        }

        if ($this->relationLoaded('assignedUsersPivot')) {
            $structure['pivot'] = $this->assignedUsersPivot;
        }

        if ($this->relationLoaded('assignedUsers')) {
            $assignedUsers = $this->assignedUsers;
            $structure['assigned_users'] = UserResource::collection($assignedUsers);
        }

        return $structure;
    }
}
