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
 * @see \App\Http\Controllers\Api\Company\Subscription\SubscriptionTrialController
 *      to the tested controller
 */
class SubscriptionTrialTest extends TestCase
{
    use WithFaker;

    /**
     * Test start trial
     * @return void
     * @see \App\Http\Controllers\Api\Company\Subscription\SubscriptionTrialController::start
     *      to the tested method
     */
    public function test_start_trial_on_availability_true(): void
    {
        $this->actingAs(
            $user = User::factory()
                ->owner()
                ->create()
        );

        $subscriptionPlanPeriod = SubscriptionPlanPeriod::factory()->trial()->create();

        $response = $this->postJson('/api/dashboard/companies/subscriptions/trial/start');
        $response->assertStatus(201);
        $response->assertJson(function (AssertableJson $json) {
            $json->has('status');
            $json->has('message');
        });

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
     * Test start trial on availability false
     * @return void
     * @see \App\Http\Controllers\Api\Company\Subscription\SubscriptionTrialController::start
     *      to the tested method
     */
    public function test_start_trial_on_availability_false(): void
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

    /**
     * Test check availability to staart trial and return true
     *
     * @return void
     * @see \App\Http\Controllers\Api\Company\Subscription\SubscriptionTrialController::startTrial()
     *      to the tested method
     */
    public function test_check_availability_to_start_trial_and_return_true(): void
    {
        $this->actingAs(
            $user = User::factory()
                ->owner()
                ->create()
        );

        $response = $this->getJson('/api/dashboard/companies/subscriptions/trial/status');
        $response->assertStatus(200);
        $response->assertJson(function (AssertableJson $json) {
            $json->has('availability');
            $json->has('message');
        });
    }

    /**
     * Test check availability to staart trial and return false
     *
     * @return void
     * @see \App\Http\Controllers\Api\Company\Subscription\SubscriptionTrialController::startTrial()
     *      to the tested method
     */
    public function test_check_availability_to_start_trial_and_return_false(): void
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

        $response = $this->getJson('/api/dashboard/companies/subscriptions/trial/status');
        $response->assertStatus(200);
        $response->assertJson(function (AssertableJson $json) {
            $json->has('availability');
            $json->has('message');
        });
    }
}
