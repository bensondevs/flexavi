<?php

namespace App\Repositories\Subscription;

use App\Models\Subscription\SubscriptionPlan;
use App\Repositories\Base\BaseRepository;

class SubscriptionPlanRepository extends BaseRepository
{
    /**
     * Repository constructor method
     *
     * @return void
     */
    public function __construct()
    {
        $this->setInitModel(new SubscriptionPlan());
    }
}
