<?php

namespace Tests\Integration\Dashboard\Company\Subscription;

use App\Models\Subscription\Subscription;
use App\Models\Subscription\SubscriptionPayment;
use App\Models\Subscription\SubscriptionPlan;
use App\Models\Subscription\SubscriptionPlanPeriod;
use App\Models\User\User;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Testing\Fluent\AssertableJson;
use Tests\TestCase;

/**
 * @see \App\Http\Controllers\Api\Company\Subscription\SubscriptionController::purchase()
 *      To see the class
 */
class PurchaseSubscriptionTest extends TestCase
{
    use WithFaker;

    /**
     * Test user can purchase a subscription on first purchase
     *
     * @return void
     */
    public function test_a_user_can_purchase_a_subscription_on_first_subscription(): void
    {
        $this->actingAs(
            $user = User::factory()->owner()->create()
        );

        $subscriptionPlanPeriod = SubscriptionPlanPeriod::factory()->create();
        $response = $this->postJson('/api/dashboard/companies/subscriptions/purchase', [
            'subscription_plan_period_id' => $subscriptionPlanPeriod->id,
        ]);

        $response->assertStatus(201);

        $response->assertJson(function (AssertableJson $json) {
            $json->has('subscription');
            $json->has('subscription.id');

            // status meta
            $json->where('status', 'success');
            $json->has('message');
        });

        $this->assertDatabaseHas((new Subscription())->getTable(), [
            'company_id' => $user->owner->company->id,
            'subscription_plan_period_id' => $subscriptionPlanPeriod->id,
        ]);

        $this->assertDatabaseHas((new SubscriptionPayment())->getTable(), [
            'amount' => $subscriptionPlanPeriod->total,
        ]);
    }

    /**
     * Test user can purchase a subscription on first purchase
     *
     * @return void
     */
    public function test_a_user_can_purchase_a_subscription_after_trial_subscription(): void
    {
        $this->actingAs(
            $user = User::factory()->owner()->create()
        );

        $subscriptionPlanPeriodTrial = SubscriptionPlanPeriod::factory()
            ->trial()
            ->for(SubscriptionPlan::factory()->trial()->create())
            ->create();

        $subscription = Subscription::factory()
            ->for($user->owner->company)
            ->for($subscriptionPlanPeriodTrial)
            ->expired()
            ->create();

        $subscriptionPayment = SubscriptionPayment::factory()
            ->for($subscription)
            ->settled()
            ->create();

        $subscriptionPlanPeriod = SubscriptionPlanPeriod::factory()->create();
        $response = $this->postJson('/api/dashboard/companies/subscriptions/purchase', [
            'subscription_plan_period_id' => $subscriptionPlanPeriod->id,
        ]);

        $response->assertStatus(201);

        $response->assertJson(function (AssertableJson $json) {
            $json->has('subscription');
            $json->has('subscription.id');

            // status meta
            $json->where('status', 'success');
            $json->has('message');
        });

        $this->assertDatabaseHas((new Subscription())->getTable(), [
            'company_id' => $user->owner->company->id,
            'subscription_plan_period_id' => $subscriptionPlanPeriod->id,
        ]);

        $this->assertDatabaseHas((new SubscriptionPayment())->getTable(), [
            'amount' => $subscriptionPlanPeriod->total,
        ]);
    }
}
