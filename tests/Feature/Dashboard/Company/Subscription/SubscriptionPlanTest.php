<?php

namespace Tests\Feature\Dashboard\Company\Subscription;

use App\Models\Subscription\SubscriptionPlan;
use App\Models\User\User;
use Illuminate\Testing\Fluent\AssertableJson;
use Tests\TestCase;

/**
 * @see \App\Http\Controllers\Api\Company\Subscription\SubscriptionPlanController
 *      To see the controller
 */
class SubscriptionPlanTest extends TestCase
{
    /**
     * Test populate subscription plans
     *
     * @return void
     * @see \App\Http\Controllers\Api\Company\Subscription\SubscriptionPlanController::subscriptionPlans()
     *      To see the method under test
     */
    public function test_populate_subscription_plans(): void
    {
        $this->actingAs(
            $user = User::factory()
                ->owner()
                ->create()
        );

        $plans = SubscriptionPlan::factory()->count(rand(1, 10))->create();

        $response = $this->getJson('/api/dashboard/companies/subscriptions/plans');
        $response->assertStatus(200);
        $response->assertJson(function (AssertableJson $json) use ($plans) {
            $json->has('subscription_plans', $plans->count() + 1)
                ->etc();
            $json->whereType('subscription_plans', 'array');
        });
    }

    /**
     * Test view subscription plan
     *
     * @return void
     * @see \App\Http\Controllers\Api\Company\Subscription\SubscriptionPlanController::view()
     *      To see the method under test
     */
    public function test_view_subscription_plan(): void
    {
        $this->actingAs(
            $user = User::factory()
                ->owner()
                ->create()
        );

        $plan = SubscriptionPlan::factory()->create();

        $response = $this->getJson(urlWithParams('/api/dashboard/companies/subscriptions/plans/view', [
            'subscription_plan_id' => $plan->id
        ]));
        $response->assertStatus(200);
        $response->assertJson(function (AssertableJson $json) use ($plan) {
            $json->has('subscription_plan')
                ->etc();
            $json->whereType('subscription_plan', 'array');
        });
    }
}
