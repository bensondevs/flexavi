<?php

namespace App\Repositories\Permission;

use App\Models\Permission\ModulePermission;
use App\Repositories\Base\BaseRepository;

class ModulePermissionRepository extends BaseRepository
{
    /**
     * Repository constructor method
     *
     * @return void
     */
    public function __construct()
    {
        $this->setInitModel(new ModulePermission());
    }

}
