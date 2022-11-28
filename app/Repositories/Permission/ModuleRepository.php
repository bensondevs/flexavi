<?php

namespace App\Repositories\Permission;

use App\Models\Permission\Module;
use App\Repositories\Base\BaseRepository;

class ModuleRepository extends BaseRepository
{
    /**
     * Repository constructor method
     *
     * @return void
     */
    public function __construct()
    {
        $this->setInitModel(new Module());
    }

}
