<?php

namespace Tests\Unit\Models\SubscriptionPlanPeriod;

use App\Models\Subscription\{SubscriptionPlan, SubscriptionPlanPeriod};
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

/**
 * @see \App\Models\Subscription\SubscriptionPlanPeriod
 *      To see model class
 */
class SubscriptionPlanPeriodTest extends TestCase
{
    use WithFaker;

    /**
     * Test it subscription plan period has subscription plan relationship
     *
     * @test
     * @return void
     * @see \App\Models\Subscription\SubscriptionPlanPeriod::subscriptionPlan()
     *      To see model relationship
     */
    public function it_subscription_plan_period_has_subscription_plan_relationship(): void
    {
        $subscriptionPlan = SubscriptionPlan::factory()->create();
        $subscriptionPlanPeriod = SubscriptionPlanPeriod::factory()->for($subscriptionPlan)->create();

        $this->assertInstanceOf(SubscriptionPlan::class, $subscriptionPlanPeriod->subscriptionPlan);
        $this->assertInstanceOf(BelongsTo::class, $subscriptionPlanPeriod->subscriptionPlan());
        $this->assertEquals($subscriptionPlan->fresh(), $subscriptionPlanPeriod->subscriptionPlan);
    }
}
