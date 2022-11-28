<?php

namespace Tests\Feature\Dashboard\Company\Subscription;

use App\Models\Subscription\Subscription;
use App\Models\Subscription\SubscriptionPayment;
use App\Models\User\User;
use App\Services\Mollie\PaymentService;
use Mollie\Api\Exceptions\ApiException;
use Mollie\Api\Resources\Payment;
use Tests\TestCase;

/**
 * @see \App\Http\Controllers\ThirdPartyCallback\Mollie\PaymentWebhookController
 *   To see the controller that handles this test.
 */
class MollieWebhookTest extends TestCase
{
    /**
     * Test call webhook on open status
     *
     * @return void
     * @throws ApiException
     */
    public function test_call_webhook_from_mollie_request(): void
    {
        $user = User::factory()
            ->owner()
            ->create();

        $company = $user->owner->company;

        $subscription = Subscription::factory()->for($company)->create();

        $payment = SubscriptionPayment::factory()->for($subscription)->create();

        $payment = $payment->fresh();

        $molliePayment = (new PaymentService())->find($payment);

        if (!$molliePayment instanceof Payment) {
            $this->fail('Failed to find mollie payment record');
        }

        $response = $this->postJson('/mollie/payment/webhook', [
            'id' => $payment->payment_gateway_payment_id
        ]);
        $response->assertStatus(200);
    }
}
