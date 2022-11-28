<?php

namespace App\Repositories\Worklist;

use App\Models\Worklist\WorklistEmployee;
use App\Repositories\Base\BaseRepository;

class WorklistEmployeeRepository extends BaseRepository
{
    /**
     * Repository constructor method
     *
     * @return void
     */
    public function __construct()
    {
        $this->setInitModel(new WorklistEmployee());
    }
}
