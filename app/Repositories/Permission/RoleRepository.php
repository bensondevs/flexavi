<?php

namespace App\Repositories\Permission;

use App\Models\Permission\Role;
use App\Repositories\Base\BaseRepository;

class RoleRepository extends BaseRepository
{
    /**
     * Repository constructor method
     *
     * @return void
     */
    public function __construct()
    {
        $this->setInitModel(new Role());
    }


}
