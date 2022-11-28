<?php

namespace Tests\Unit\Services\Subscription\SubscriptionTrialService;

use App\Enums\Subscription\SubscriptionStatus;
use App\Enums\SubscriptionPayment\SubscriptionPaymentStatus;
use App\Models\Subscription\Subscription;
use App\Models\Subscription\SubscriptionPayment;
use App\Models\Subscription\SubscriptionPlan;
use App\Models\Subscription\SubscriptionPlanPeriod;
use App\Models\User\User;

/**
 * @see \App\Services\Subscription\SubscriptionTrialService::start()
 *      This is a test class for SubscriptionTrialService::start
 */
class StartTest extends SubscriptionTrialServiceTest
{
    /**
     * Ensure method is created subscription
     *
     *
     * @test
     * @return void
     */
    public function ensure_method_is_created_subscription(): void
    {
        $this->actingAs(
            $user = User::factory()
                ->owner()
                ->create()
        );
        $subscriptionPlan = SubscriptionPlan::factory()->trial()->create();
        $subscriptionPlanPeriod = SubscriptionPlanPeriod::factory()->for($subscriptionPlan)->trial()->create();
        Subscription::forCompany($user->owner->company)->forceDelete();
        $this->service()->start($user->owner->company);

        $this->assertDatabaseHas((new Subscription()), [
            'company_id' => $user->owner->company->id,
            'subscription_plan_period_id' => $subscriptionPlanPeriod->id,
            'status' => SubscriptionStatus::Active
        ]);

        $this->assertDatabaseHas((new SubscriptionPayment()), [
            'status' => SubscriptionPaymentStatus::Settled,
            'amount' => 0
        ]);
    }
}
