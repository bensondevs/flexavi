<?php

namespace Tests\Feature\Dashboard\Company\Subscription;

use App\Models\User\User;
use Illuminate\Testing\Fluent\AssertableJson;
use Tests\TestCase;

class MolliePaymentMethodTest extends TestCase
{
    /**
     * Test populate mollie payment methods
     *
     * @return void
     */
    public function test_populate_mollie_payment_methods(): void
    {
        $this->actingAs(
            $user = User::factory()
                ->owner()
                ->create()
        );

        $response = $this->getJson('/api/dashboard/companies/subscriptions/payment_methods');
        $response->assertStatus(200);
        $response->assertJson(function (AssertableJson $json) {
            $json->has('payment_methods');
        });
    }
}
