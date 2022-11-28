<?php

namespace Tests\Unit\Services\Subscription\SubscriptionBackgroundProcessService;

use App\Models\Subscription\Subscription;
use App\Models\Subscription\SubscriptionPayment;
use App\Models\Subscription\SubscriptionPlan;
use App\Models\Subscription\SubscriptionPlanPeriod;
use Illuminate\Foundation\Testing\WithFaker;

/**
 * @see \App\Services\Subscription\SubscriptionBackgroundProcessService::checkSubscriptionsStatus()
 *      To the tested service method.
 */
class CheckSubscriptionsStatusTest extends SubscriptionBackgroundProcessServiceTest
{
    use WithFaker;

    /**
     * Test set expired on free trial subscription.
     *
     * @test
     */
    public function test_set_expired_on_free_trial_subscription(): void
    {
        $subscriptionPlan = SubscriptionPlan::factory()->trial()->create();
        $subscriptionPlanPeriod = SubscriptionPlanPeriod::factory()->for($subscriptionPlan)->trial()->create();
        $subscriptionsId = [];
        for ($i = 0; $i < 2; $i++) {
            $subscription = Subscription::factory()->for($subscriptionPlanPeriod)->active()->create();
            $subscriptionsId[] = $subscription->id;
            $subscription->subscription_end = now()->subDays(rand(1, 3));
            $subscription->saveQuietly();
        }

        Subscription::whereNotIn('id', $subscriptionsId)->forceDelete();
        $this->service()->checkSubscriptionsStatus();
        foreach (Subscription::whereIn('id', $subscriptionsId)->get() as $subscription) {
            $this->assertTrue($subscription->isExpired());
        }
    }

    /**
     * Test create renewal subscription.
     *
     * @return void
     */
    public function test_create_renewal_on_expired_subscription(): void
    {
        $subscriptionPlan = SubscriptionPlan::factory()->create();
        $subscriptionPlanPeriod = SubscriptionPlanPeriod::factory()->for($subscriptionPlan)->create();
        $subscriptionsId = [];
        for ($i = 0; $i < 2; $i++) {
            $subscription = Subscription::factory()->for($subscriptionPlanPeriod)->active()->create();
            $subscriptionsId[] = $subscription->id;
            $subscription->subscription_end = now()->subDays(rand(1, 3));
            $subscription->saveQuietly();
        }
        Subscription::whereNotIn('id', $subscriptionsId)->forceDelete();
        $this->service()->checkSubscriptionsStatus();
        foreach (Subscription::whereIn('id', $subscriptionsId)->get() as $subscription) {
            $this->assertDatabaseHas((new Subscription())->getTable(), [
                'previous_subscription_id' => $subscription->id,
            ]);
        }
    }

    /**
     * Test set active on first activable subscription on expired subscription.
     *
     * @return void
     */
    public function test_activate_other_activable_subscription_on_expired_subscription(): void
    {
        $subscriptionPlan = SubscriptionPlan::factory()->create();
        $subscriptionPlanPeriod = SubscriptionPlanPeriod::factory()->for($subscriptionPlan)->create();
        $subscriptionsId = [];
        for ($i = 0; $i < 2; $i++) {
            $subscription = Subscription::factory()->for($subscriptionPlanPeriod)->active()->create();
            $subscriptionsId[] = $subscription->id;
            $subscription->subscription_end = now()->subDays(rand(1, 3));
            $subscription->saveQuietly();
        }
        Subscription::whereNotIn('id', $subscriptionsId)->forceDelete();
        foreach (Subscription::whereIn('id', $subscriptionsId)->get() as $subscription) {
            $subscription = Subscription::factory()->for($subscription->company)->inactive()->create();
            $subscriptionPayment = SubscriptionPayment::factory()->for($subscription)->withoutPaymentGateway()->settled()->create();
        }
        $this->service()->checkSubscriptionsStatus();
        foreach (Subscription::whereIn('id', $subscriptionsId)->get() as $subscription) {
            $this->assertTrue($subscription->isExpired());
            $activableSubsription = Subscription::whereNot('id', $subscription->id)->forCompany($subscription->company)->first();
            $this->assertTrue($activableSubsription->isActive());
        }
    }
}
