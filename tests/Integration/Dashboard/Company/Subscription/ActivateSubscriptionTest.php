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
 * @see \App\Http\Controllers\Api\Company\Subscription\SubscriptionController::activate()
 *      To see controller method
 */
class ActivateSubscriptionTest extends TestCase
{
    use WithFaker;

    /**
     * testing activates a subscription and in the company there is only 1 subscription
     *
     * @return void
     */
    public function test_success_activate_a_subscription_and_in_the_company_there_is_only_one_subscription(): void
    {
        $this->actingAs(
            $user = User::factory()
                ->owner()
                ->create()
        );

        Subscription::forCompany($user->owner->company)->first()->setTerminated();

        $subscriptionPlanPeriod = SubscriptionPlanPeriod::factory()->create();
        $subscription = Subscription::factory()->for($subscriptionPlanPeriod)->for($user->owner->company)->inactive()->create();
        $subscriptionPayment = SubscriptionPayment::factory()->for($subscription)->settled()->create();

        $response = $this->postJson('/api/dashboard/companies/subscriptions/activate', [
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
            'status' => SubscriptionStatus::Active,
        ]);
    }

    /**
     * testing activates a subscription and in the company there is only 1 subscription
     *
     * @return void
     */
    public function test_failed_to_activate_a_subscription_and_in_the_company_has_other_active_subscription(): void
    {
        $this->actingAs(
            $user = User::factory()
                ->owner()
                ->create()
        );

        $subscriptionPlanPeriod = SubscriptionPlanPeriod::factory()->create();
        $subscription = Subscription::factory()->for($subscriptionPlanPeriod)->for($user->owner->company)->inactive()->create();
        $subscriptionPayment = SubscriptionPayment::factory()->for($subscription)->settled()->create();

        $response = $this->postJson('/api/dashboard/companies/subscriptions/activate', [
            'subscription_id' => $subscription->id,
        ]);

        $response->assertStatus(422);

        $this->assertDatabaseHas((new Subscription())->getTable(), [
            'company_id' => $user->owner->company->id,
            'subscription_plan_period_id' => $subscriptionPlanPeriod->id,
            'status' => SubscriptionStatus::Inactive,
        ]);
    }
}
