<?php

namespace App\Http\Resources\Permission;

use App\Models\Permission\Module;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/** @mixin Module */
class ModuleResource extends JsonResource
{
    /**
     * @param Request $request
     * @return array
     */
    public function toArray($request): array
    {
        return [
            'id' => $this->id,
            'role_id' => $this->role_id,
            'module_name' => $this->module_name,
            'module_description' => $this->module_description,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'permissions' => ModulePermissionResource::collection($this->whenLoaded('modulePermissions')),
        ];
    }
}
