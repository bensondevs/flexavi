<?php

namespace App\Http\Resources\Notification;

use App\Models\Notification\Notification;
use App\Traits\ApiCollectionResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @mixin Notification
 */
class NotificationResource extends JsonResource
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
            'type' => $this->type,
            'type_description' => $this->type_description,
            'title' => $this->formattedContent->title,
            'message' => $this->formattedContent->message,
            'body' => $this->formattedContent->body,
            'read_at' => $this->read_at,
            'created_at' => $this->created_at,
            'formatted_date' => $this->formatted_date,
            'formatted_time' => $this->formatted_time,
            'formatted_time_in_human' => $this->formatted_time_in_human,
        ];

        if ($this->relationLoaded('actor')) {
            $structure['actor'] = $this->actor;
        }

        return $structure;
    }
}
