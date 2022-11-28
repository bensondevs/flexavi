<?php

namespace App\Http\Resources\Worklist;

use App\Http\Resources\Employee\EmployeeResource;
use App\Http\Resources\Owner\OwnerResource;
use App\Models\User\User;
use Illuminate\Http\Resources\Json\JsonResource;

class WorklistEmployeeResource extends JsonResource
{
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
            'worklist_id' => $this->worklist_id,
            'user_id' => $this->user_id,
        ];

        if ($this->relationLoaded('worklist')) {
            $structure['worklist'] = $this->worklist;
        }

        if ($this->relationLoaded('user')) {
            $user = $this->user;

            if (is_null($this->user)) {
                $user = User::find($this->user_id);
            }

            $user = [
                'role' => $user->user_role,
                'userable' => $user->user_role == "owner" ? new OwnerResource($user->owner) : new EmployeeResource($user->employee->load('user'))
            ];

            $structure['user'] = $user;
        }

        return $structure;
    }
}
