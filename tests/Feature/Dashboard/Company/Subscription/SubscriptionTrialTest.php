<?php

namespace Tests\Feature\Dashboard\Company\Subscription;

use App\Models\Subscription\Subscription;
use App\Models\Subscription\SubscriptionPayment;
use App\Models\Subscription\SubscriptionPlan;
use App\Models\Subscription\SubscriptionPlanPeriod;
use App\Models\User\User;
use Illuminate\Testing\Fluent\AssertableJson;
use Tests\TestCase;

/**
 * @see \App\Http\Controllers\Api\Company\Subscription\SubscriptionTrialController
 *      To see the controller
 */
class SubscriptionTrialTest extends TestCase
{
    /**
     * Test check available trial subscription
     *
     * @return void
     * @see \App\Http\Controllers\Api\Company\Subscription\SubscriptionTrialController::status()
     *      To see the controller method
     */
    public function test_check_available_subscription_trial(): void
    {
        $this->actingAs(
            $user = User::factory()
                ->owner()
                ->create()
        );

        $response = $this->getJson('/api/dashboard/companies/subscriptions/trial/status');
        $response->assertStatus(200);
        $response->assertJson(
            fn (AssertableJson $json) => $json->where('availability', true)
                ->where('message', 'You can start a trial subscription.')
        );
    }

    /**
     * Test check unavailable trial subscription
     *
     * @return void
     * @see \App\Http\Controllers\Api\Company\Subscription\SubscriptionTrialController::status()
     *      To see the controller method
     */
    public function test_check_not_available_subscription_trial(): void
    {
        $this->actingAs(
            $user = User::factory()
                ->owner()
                ->create()
        );
        $company = $user->owner->company;

        $subscriptionPlan = SubscriptionPlan::factory()->trial()->create();
        $subscriptionPlanPeriod = SubscriptionPlanPeriod::factory()->for($subscriptionPlan)->trial()->create();
        Subscription::factory()->for($company)->for($subscriptionPlanPeriod)->create();
        $response = $this->getJson('/api/dashboard/companies/subscriptions/trial/status');
        $response->assertStatus(200);
        $response->assertJson(
            fn (AssertableJson $json) => $json->where('availability', false)
                ->where('message', 'You already have a trial subscription.')
        );
    }

    /**
     * Test start trial subscription
     *
     * @return void
     * @see \App\Http\Controllers\Api\Company\Subscription\SubscriptionTrialController::startTrial()
     *      To see the controller method
     */
    public function test_start_free_trial(): void
    {
        $this->actingAs(
            $user = User::factory()
                ->owner()
                ->create()
        );

        $subscriptionPlanPeriod = SubscriptionPlanPeriod::factory()->for(
            SubscriptionPlan::factory()->trial()->create()
        )->trial()->create();
        $response = $this->postJson('/api/dashboard/companies/subscriptions/trial/start');
        $response->assertStatus(201);
        $response->assertJson(
            fn (AssertableJson $json) => $json->where('status', 'success')
                ->where('message', 'Successfully started a trial subscription.')
        );

        $this->assertDatabaseHas((new Subscription())->getTable(), [
            'company_id' => $user->owner->company->id,
            'subscription_plan_period_id' => $subscriptionPlanPeriod->id,
        ]);

        $this->assertDatabaseHas((new SubscriptionPayment())->getTable(), [
            'amount' => 0,
            'payment_gateway_type' => null,
        ]);
    }

    /**
     * Test failed start trial subscription
     *
     * @return void
     * @see \App\Http\Controllers\Api\Company\Subscription\SubscriptionTrialController::startTrial()
     *      To see the controller method
     */
    public function test__failed_start_free_trial(): void
    {
        $this->actingAs(
            $user = User::factory()
                ->owner()
                ->create()
        );

        $subscriptionPlanPeriod = SubscriptionPlanPeriod::factory()->for(
            SubscriptionPlan::factory()->trial()->create()
        )->trial()->create();
        Subscription::factory()->for($user->owner->company)->for($subscriptionPlanPeriod)->create();
        $response = $this->postJson('/api/dashboard/companies/subscriptions/trial/start');
        $response->assertStatus(422);
    }
}
