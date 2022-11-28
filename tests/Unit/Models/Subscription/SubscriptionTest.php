<?php

namespace Tests\Unit\Models\Subscription;

use App\Enums\Subscription\SubscriptionStatus;
use App\Models\Company\Company;
use App\Models\Subscription\Subscription;
use App\Models\Subscription\SubscriptionPayment;
use App\Models\Subscription\SubscriptionPlanPeriod;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

/**
 * @see \App\Models\Subscription\Subscription
 *      To see model class
 */
class SubscriptionTest extends TestCase
{
    use WithFaker;

    /**
     * Testing it subscription has company relationship
     *
     * @test
     * @return void
     * @see \App\Models\Subscription\Subscription::company()
     *      To see model relationship
     */
    public function it_subscription_has_company_relationship(): void
    {
        $company = Company::factory()->create();
        $subscription = Subscription::factory()->for($company)->create();
        $this->assertInstanceOf(Company::class, $subscription->company);
        $this->assertInstanceOf(BelongsTo::class, $subscription->company());
        $this->assertEquals($subscription->company, $company->fresh());
    }

    /**
     * Testing it subscription has payment relationship
     *
     * @test
     * @return void
     * @see \App\Models\Subscription\Subscription::payment()
     *      To see model relationship
     */
    public function it_subscription_has_payment_relationship(): void
    {
        $subscription = Subscription::factory()->create();
        $payment = SubscriptionPayment::factory()->for($subscription)->create();
        $this->assertInstanceOf(HasOne::class, $subscription->payment());
    }

    /**
     * Testing it subscription has subscription plan period relationship
     *
     * @test
     * @return void
     * @see \App\Models\Subscription\Subscription::subscriptionPlanPeriod()
     *      To see model relationship
     */
    public function it_subscription_has_subscription_plan_period_relationship(): void
    {
        $subscription = Subscription::factory()->create();
        $subscriptionPlanPeriod = $subscription->subscriptionPlanPeriod;
        $this->assertInstanceOf(SubscriptionPlanPeriod::class, $subscription->subscriptionPlanPeriod);
        $this->assertInstanceOf(BelongsTo::class, $subscription->subscriptionPlanPeriod());
        $this->assertEquals($subscriptionPlanPeriod->fresh(), $subscription->subscriptionPlanPeriod);
    }

    /**
     * Testing it `isInactive` method valid
     *
     * @return void
     * @test
     * @see \App\Models\Subscription\Subscription::isInactive()
     *      To see model method
     */
    public function ensure_is_inactive_method_is_valid(): void
    {
        $subscription = Subscription::factory()->inactive()->create();
        $this->assertDatabaseHas((new Subscription())->getTable(), [
            'id' => $subscription->id,
            'status' => SubscriptionStatus::Inactive
        ]);
        $this->assertTrue($subscription->isInactive());
    }

    /**
     * Testing it `isActive` method valid
     *
     * @return void
     * @test
     * @see \App\Models\Subscription\Subscription::isActive()
     *      To see model method
     */
    public function ensure_is_active_method_is_valid(): void
    {
        $subscription = Subscription::factory()->active()->create();
        $this->assertDatabaseHas((new Subscription())->getTable(), [
            'id' => $subscription->id,
            'status' => SubscriptionStatus::Active
        ]);
        $this->assertTrue($subscription->isActive());
    }

    /**
     * Testing it `isExpired` method valid
     *
     * @return void
     * @test
     * @see \App\Models\Subscription\Subscription::isExpired()
     *      To see model method
     */
    public function ensure_is_expired_method_is_valid(): void
    {
        $subscription = Subscription::factory()->expired()->create();
        $this->assertDatabaseHas((new Subscription())->getTable(), [
            'id' => $subscription->id,
            'status' => SubscriptionStatus::Expired
        ]);
        $this->assertTrue($subscription->isExpired());
    }

    /**
     * Testing it `isTerminated` method valid
     *
     * @return void
     * @test
     * @see \App\Models\Subscription\Subscription::isTerminated()
     *      To see model method
     */
    public function ensure_is_terminated_method_is_valid(): void
    {
        $subscription = Subscription::factory()->terminated()->create();
        $this->assertDatabaseHas((new Subscription())->getTable(), [
            'id' => $subscription->id,
            'status' => SubscriptionStatus::Terminated
        ]);
        $this->assertTrue($subscription->isTerminated());
    }

    /**
     * Testing it `setTerminated` method is valid
     *
     * @return void
     * @test
     * @see \App\Models\Subscription\Subscription::setTerminated()
     *      To see model method
     */
    public function ensure_set_terminated_method_is_valid(): void
    {
        $subscription = Subscription::factory()->active()->create();
        $subscription->setTerminated();
        $this->assertDatabaseHas((new Subscription())->getTable(), [
            'id' => $subscription->id,
            'status' => SubscriptionStatus::Terminated
        ]);
    }

    /**
     * Testing ensure `setExpired` method is valid
     *
     * @return void
     * @test
     * @see \App\Models\Subscription\Subscription::setExpired()
     *      To see model method
     */
    public function ensure_set_expired_method_is_valid(): void
    {
        $subscription = Subscription::factory()->active()->create();
        $subscription->setExpired();
        $this->assertDatabaseHas((new Subscription())->getTable(), [
            'id' => $subscription->id,
            'status' => SubscriptionStatus::Expired
        ]);
    }

    /**
     * Testing ensure `setActive` method is valid
     *
     * @return void
     * @test
     * @see \App\Models\Subscription\Subscription::setActive()
     *      To see model method
     */
    public function ensure_set_active_method_is_valid(): void
    {
        $subscription = Subscription::factory()->active()->create();
        $subscription->setActive();
        $this->assertDatabaseHas((new Subscription())->getTable(), [
            'id' => $subscription->id,
            'status' => SubscriptionStatus::Active
        ]);
    }
}
