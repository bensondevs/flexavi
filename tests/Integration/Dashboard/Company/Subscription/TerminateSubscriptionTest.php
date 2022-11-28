<?php

namespace Tests\Integration\Dashboard\Company\Subscription;

use App\Enums\Subscription\SubscriptionStatus;
use App\Models\Subscription\Subscription;
use App\Models\Subscription\SubscriptionPayment;
use App\Models\Subscription\SubscriptionPlanPeriod;
use App\Models\User\User;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Testing\Fluent\AssertableJson;
use Tests\TestCase;

/**
 * @see \App\Http\Controllers\Api\Company\Subscription\SubscriptionController::terminate()
 *      To see controller method
 */
class TerminateSubscriptionTest extends TestCase
{
    use WithFaker;

    /**
     * testing success to terminate subscription
     *
     * @return void
     */
    public function test_success_terminate_subscription(): void
    {
        $this->actingAs(
            $user = User::factory()
                ->owner()
                ->create()
        );

        $subscriptionPlanPeriod = SubscriptionPlanPeriod::factory()->create();
        $subscription = Subscription::factory()->for($subscriptionPlanPeriod)->for($user->owner->company)->active()->create();
        $subscriptionPayment = SubscriptionPayment::factory()->for($subscription)->settled()->create();

        $response = $this->postJson('/api/dashboard/companies/subscriptions/terminate', [
            'subscription_id' => $subscription->id,
        ]);

        $response->assertStatus(201);
        $response->assertJson(function (AssertableJson $json) {
            $json->where('status', 'success');
            $json->has('message');
        });

        $this->assertDatabaseHas((new Subscription())->getTable(), [
            'company_id' => $user->owner->company->id,
            'subscription_plan_period_id' => $subscriptionPlanPeriod->id,
            'status' => SubscriptionStatus::Terminated,
        ]);
    }

    /**
     * Test failed terminate subscription because the subscription that was sent been terminated
     *
     * @return void
     */
    public function test_failed_terminate_subscription_because_the_subscription_that_was_sent_been_terminated(): void
    {
        $this->actingAs(
            $user = User::factory()
                ->owner()
                ->create()
        );

        $subscriptionPlanPeriod = SubscriptionPlanPeriod::factory()->create();
        $subscription = Subscription::factory()->for($subscriptionPlanPeriod)->for($user->owner->company)->terminated()->create();
        $subscriptionPayment = SubscriptionPayment::factory()->for($subscription)->settled()->create();

        $response = $this->postJson('/api/dashboard/companies/subscriptions/terminate', [
            'subscription_id' => $subscription->id,
        ]);

        $response->assertStatus(404);
    }
}
