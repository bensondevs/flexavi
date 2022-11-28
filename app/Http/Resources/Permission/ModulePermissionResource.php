<?php

namespace App\Http\Resources\Permission;

use App\Models\Permission\ModulePermission;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/** @mixin ModulePermission */
class ModulePermissionResource extends JsonResource
{
    /**
     * @param Request $request
     * @return array
     */
    public function toArray($request): array
    {
        return [
            'id' => $this->id,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'module_id' => $this->module_id,
            'permission_id' => $this->permission_id,
            'permission' => $this->permission
        ];
    }
}
