<?php

namespace App\Repositories\Subscription;

use App\Models\Subscription\SubscriptionPlanPeriod;
use App\Repositories\Base\BaseRepository;

class SubscriptionPlanPeriodRepository extends BaseRepository
{
	/**
	 * Repository constructor method
	 *
	 * @return void
	 */
	public function __construct()
	{
		$this->setInitModel(new SubscriptionPlanPeriod);
	}
}
