<?php

namespace App\Repositories\Worklist;

use App\Models\Worklist\WorklistCar;
use App\Repositories\Base\BaseRepository;

class WorklistCarRepository extends BaseRepository
{
    /**
     * Repository constructor method
     *
     * @return void
     */
    public function __construct()
    {
        $this->setInitModel(new WorklistCar());
    }
}
