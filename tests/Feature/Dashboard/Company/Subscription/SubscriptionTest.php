<?php

namespace Tests\Feature\Dashboard\Company\Subscription;

use App\Enums\Subscription\SubscriptionStatus;
use App\Models\Subscription\Subscription;
use App\Models\Subscription\SubscriptionPayment;
use App\Models\Subscription\SubscriptionPlanPeriod;
use App\Models\User\User;
use Illuminate\Testing\Fluent\AssertableJson;
use Tests\TestCase;

/**
 * @see \App\Http\Controllers\Api\Company\Subscription\SubscriptionController
 *      To see the controller
 */
class SubscriptionTest extends TestCase
{
    /**
     * Test purchase company subscription
     *
     * @return void
     * @see \App\Http\Controllers\Api\Company\Subscription\SubscriptionController::purchase()
     *     To see the method
     */
    public function test_purchase_subscription(): void
    {
        $this->actingAs(
            $user = User::factory()
                ->owner()
                ->create()
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
    }

    /**
     * Test activate company subscription
     *
     * @return void
     *
     * @see \App\Http\Controllers\Api\Company\Subscription\SubscriptionController::activate()
     *      To see the method
     */
    public function test_activate_subscription(): void
    {
        $this->actingAs(
            $user = User::factory()
                ->owner()
                ->create()
        );

        Subscription::forCompany($user->owner->company)->first()->setTerminated();
        $subscriptionPlanPeriod = SubscriptionPlanPeriod::factory()->create();
        $subscription = Subscription::factory()
            ->for($user->owner->company)
            ->for($subscriptionPlanPeriod)
            ->terminated()
            ->create();

        $response = $this->postJson('/api/dashboard/companies/subscriptions/activate', [
            'subscription_id' => $subscription->id,
        ]);
        $response->assertStatus(201);
        $response->assertJson(function (AssertableJson $json) {
            // status meta
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
     * Test terminate company subscription
     *
     * @return void
     *
     * @see \App\Http\Controllers\Api\Company\Subscription\SubscriptionController::terminate()
     *      To see the method
     */
    public function test_terminate_subscription(): void
    {
        $this->actingAs(
            $user = User::factory()
                ->owner()
                ->create()
        );

        $subscriptionPlanPeriod = SubscriptionPlanPeriod::factory()->create();
        $subscription = Subscription::factory()
            ->for($user->owner->company)
            ->for($subscriptionPlanPeriod)
            ->active()
            ->create();

        $response = $this->postJson('/api/dashboard/companies/subscriptions/terminate', [
            'subscription_id' => $subscription->id,
        ]);
        $response->assertStatus(201);
        $response->assertJson(function (AssertableJson $json) {
            // status meta
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
     * Test get company subscription
     *
     * @return void
     *
     * @see \App\Http\Controllers\Api\Company\Subscription\SubscriptionController::view()
     *      To see the method
     */
    public function test_view_subscription(): void
    {
        $this->actingAs(
            $user = User::factory()
                ->owner()
                ->create()
        );

        $subscriptionPlanPeriod = SubscriptionPlanPeriod::factory()->create();
        $subscription = Subscription::factory()
            ->for($user->owner->company)
            ->for($subscriptionPlanPeriod)
            ->active()
            ->create();

        $response = $this->getJson(urlWithParams('/api/dashboard/companies/subscriptions/view', [
            'subscription_id' => $subscription->id,
        ]));
        $response->assertStatus(200);
        $response->assertJson(function (AssertableJson $json) use ($subscription) {
            $json->has('subscription');
            $json->has('subscription.id');
            $json->where('subscription.id', $subscription->id);
        });
    }

    /**
     * Test get company subscription
     *
     * @return void
     *
     * @see \App\Http\Controllers\Api\Company\Subscription\SubscriptionController::activeSubscription()
     *      To see the method
     */
    public function test_view_active_subscription(): void
    {
        $this->actingAs(
            $user = User::factory()
                ->owner()
                ->create()
        );

        Subscription::whereNotNull('id')->delete();

        $subscriptionPlanPeriod = SubscriptionPlanPeriod::factory()->create();
        $subscription = Subscription::factory()
            ->for($user->owner->company)
            ->for($subscriptionPlanPeriod)
            ->active()
            ->create();

        $response = $this->getJson('/api/dashboard/companies/subscriptions/active');
        $response->assertStatus(200);
        $response->assertJson(function (AssertableJson $json) use ($subscription) {
            $json->has('subscription');
            $json->has('subscription.id');
            $json->where('subscription.id', $subscription->id);
        });
    }

    /**
     * Test populate active company subscriptions
     *
     * @return void
     *
     * @see \App\Http\Controllers\Api\Company\Subscription\SubscriptionController::companyActiveSubscriptions()
     *      To see the method
     */
    public function test_populate_active_subscriptions(): void
    {
        $this->actingAs(
            $user = User::factory()
                ->owner()
                ->create()
        );

        $subscriptionPlanPeriod = SubscriptionPlanPeriod::factory()->create();
        $subscription = Subscription::factory()
            ->for($user->owner->company)
            ->for($subscriptionPlanPeriod)
            ->active()
            ->create();
        $payment = SubscriptionPayment::factory()->for($subscription)->settled()->create();

        $response = $this->getJson('/api/dashboard/companies/subscriptions/actives');
        $response->assertStatus(200);
        $response->assertJson(function (AssertableJson $json) use ($user) {
            $json->has('subscriptions');
            $json->whereType('subscriptions.data', 'array');

            // pagination meta
            $json->has('subscriptions.current_page');
            $json->has('subscriptions.first_page_url');
            $json->has('subscriptions.from');
            $json->has('subscriptions.last_page');
            $json->has('subscriptions.last_page_url');
            $json->has('subscriptions.links');
            $json->has('subscriptions.next_page_url');
            $json->has('subscriptions.path');
            $json->has('subscriptions.per_page');
            $json->has('subscriptions.prev_page_url');
            $json->has('subscriptions.to');
            $json->has('subscriptions.total');
        });
    }

    /**
     * Test populate company subscriptions / histories
     *
     * @return void
     *
     * @see \App\Http\Controllers\Api\Company\Subscription\SubscriptionController::companySubscriptions()
     *      To see the method
     */
    public function test_populate_subscriptions(): void
    {
        $this->actingAs(
            $user = User::factory()
                ->owner()
                ->create()
        );

        $subscriptionPlanPeriod = SubscriptionPlanPeriod::factory()->create();
        $subscription = Subscription::factory()
            ->for($user->owner->company)
            ->for($subscriptionPlanPeriod)
            ->active()
            ->create();

        $response = $this->getJson('/api/dashboard/companies/subscriptions/histories');
        $response->assertStatus(200);
        $response->assertJson(function (AssertableJson $json) use ($user) {
            $json->has('subscriptions');
            $json->whereType('subscriptions.data', 'array');

            // pagination meta
            $json->has('subscriptions.current_page');
            $json->has('subscriptions.first_page_url');
            $json->has('subscriptions.from');
            $json->has('subscriptions.last_page');
            $json->has('subscriptions.last_page_url');
            $json->has('subscriptions.links');
            $json->has('subscriptions.next_page_url');
            $json->has('subscriptions.path');
            $json->has('subscriptions.per_page');
            $json->has('subscriptions.prev_page_url');
            $json->has('subscriptions.to');
            $json->has('subscriptions.total');
        });
    }
}
