<?php

namespace Tests\Feature\Dashboard\Company\Subscription;

use App\Models\Subscription\SubscriptionPlan;
use App\Models\Subscription\SubscriptionPlanPeriod;
use App\Models\User\User;
use Illuminate\Testing\Fluent\AssertableJson;
use Tests\TestCase;

/**
 * @see \App\Http\Controllers\Api\Company\Subscription\SubscriptionPlanPeriodController
 *      To see the controller
 */
class SubscriptionPlanPeriodTest extends TestCase
{
    /**
     * Test populate subscription plans
     *
     * @return void
     * @see \App\Http\Controllers\Api\Company\Subscription\SubscriptionPlanPeriodController::subscriptionPlanPeriods()
     *      To see the method under test
     */
    public function test_populate_subscription_plan_periods(): void
    {
        $this->actingAs(
            $user = User::factory()
                ->owner()
                ->create()
        );

        $subscriptionPlan = SubscriptionPlan::factory()
            ->create();

        $subscriptionPlanPeriods = SubscriptionPlanPeriod::factory()->for($subscriptionPlan)->count(rand(1, 10))->create();

        $response = $this->getJson(urlWithParams('/api/dashboard/companies/subscriptions/plans/periods', [
            'subscription_plan_id' => $subscriptionPlan->id,
        ]));
        $response->assertStatus(200);
        $response->assertJson(function (AssertableJson $json) use ($subscriptionPlanPeriods) {
            $json->has('subscription_plan_periods', $subscriptionPlanPeriods->count())
                ->etc();
            $json->whereType('subscription_plan_periods', 'array');
        });
    }

    /**
     * Test view subscription plan period
     *
     * @return void
     * @see \App\Http\Controllers\Api\Company\Subscription\SubscriptionPlanPeriodController::view()
     *      To see the method under test
     */
    public function test_view_subscription_plan_period(): void
    {
        $this->actingAs(
            $user = User::factory()
                ->owner()
                ->create()
        );

        $subscriptionPlan = SubscriptionPlan::factory()
            ->create();

        $subscriptionPlanPeriod = SubscriptionPlanPeriod::factory()->for($subscriptionPlan)->create();

        $response = $this->getJson(urlWithParams('/api/dashboard/companies/subscriptions/plans/periods/view', [
            'subscription_plan_period_id' => $subscriptionPlanPeriod->id
        ]));
        $response->assertStatus(200);
        $response->assertJson(function (AssertableJson $json) {
            $json->has('subscription_plan_period')
                ->etc();
            $json->whereType('subscription_plan_period', 'array');
        });
    }
}
