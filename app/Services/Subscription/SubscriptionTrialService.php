<?php

namespace App\Services\Subscription;

use App\Enums\Subscription\SubscriptionStatus;
use App\Models\Company\Company;
use App\Models\Subscription\SubscriptionPlanPeriod;
use App\Repositories\Subscription\SubscriptionRepository;

/**
 * @see \Tests\Unit\Services\Subscription\SubscriptionTrialService\SubscriptionTrialServiceTest
 *      To the test class for this class.
 */
class SubscriptionTrialService
{
    /**
     * Subscription Repository Class Container
     *
     * @var SubscriptionRepository
     */
    private SubscriptionRepository $subscriptionRepository;

    /**
     * Service constructor method
     */
    public function __construct(SubscriptionRepository $subscriptionRepository)
    {
        $this->subscriptionRepository = $subscriptionRepository;
    }

    /**
     * Start trial subscription
     *
     * @param Company $company
     * @return SubscriptionRepository
     */
    public function start(Company $company): SubscriptionRepository
    {
        $subscriptionPlanPeriod = SubscriptionPlanPeriod::where('is_trial', true)->first();
        $subscriptionData = [
            'company_id' => $company->id,
            'subscription_plan_period_id' => $subscriptionPlanPeriod->id,
            'status' => SubscriptionStatus::Active,
            'subscription_start' => now(),
            'subscription_end' => now()->addDays($subscriptionPlanPeriod->days_duration),
        ];
        $this->subscriptionRepository->startTrial($subscriptionData);
        return $this->subscriptionRepository;
    }
}
