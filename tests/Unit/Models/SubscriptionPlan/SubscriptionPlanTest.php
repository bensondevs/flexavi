<?php

namespace Tests\Unit\Models\SubscriptionPlan;

use App\Models\Subscription\{SubscriptionPlan, SubscriptionPlanPeriod};
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Relations\{HasMany, HasOne};
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

/**
 * @see \App\Models\Subscription\SubscriptionPlan
 *      To see model class
 */
class SubscriptionPlanTest extends TestCase
{
    use WithFaker;

    /**
     *
     * Testing ensure subscription plan has many periods
     *
     * @test
     * @return void
     * @see \App\Models\Subscription\SubscriptionPlan::subscriptionPlanPeriods()
     *      To see model relationship
     */
    public function ensure_subscription_plan_has_many_subscription_plan_periods(): void
    {
        $plan = SubscriptionPlan::factory()
            ->hasSubscriptionPlanPeriods(3)
            ->create();

        $periods = SubscriptionPlanPeriod::all();
        $this->assertEquals($plan->subscriptionPlanPeriods, $periods);
        $this->assertInstanceOf(SubscriptionPlanPeriod::class, $plan->subscriptionPlanPeriods->first());
        $this->assertInstanceOf(SubscriptionPlanPeriod::class, $plan->subscriptionPlanPeriods()->first());
        $this->assertInstanceOf(Collection::class, $plan->subscriptionPlanPeriods);
        $this->assertInstanceOf(HasMany::class, $plan->subscriptionPlanPeriods());
    }

    /**
     *
     * Testing ensure subscription has higher discount of period
     *
     * @test
     * @return void
     * @see \App\Models\Subscription\SubscriptionPlan::higherDiscountOfPeriod()
     *      To see model relationship
     */
    public function ensure_subscription_plan_has_higher_discount_on_period(): void
    {
        $plan = SubscriptionPlan::factory()
            ->hasSubscriptionPlanPeriods(3)
            ->create();

        $higherDiscount = SubscriptionPlanPeriod::orderBy('discount', 'DESC')->limit(1)->first();
        $this->assertEquals($plan->higherDiscountOfPeriod, $higherDiscount);
        $this->assertInstanceOf(HasOne::class, $plan->higherDiscountOfPeriod());
    }
}
