<?php

namespace App\Http\Controllers\ThirdPartyCallback\Mollie;

use App\Http\Controllers\Controller;
use App\Http\Requests\Company\Subscriptions\WebhookRequest;
use App\Services\Mollie\WebhookPaymentService;
use Mollie\Api\Exceptions\ApiException;

/**
 * @see \Tests\Feature\Dashboard\Company\Subscription\MollieWebhookTest
 *      To see the test that covers this controller.
 */
class PaymentWebhookController extends Controller
{
    /**
     * Mollie webhook payment service Class Container
     *
     * @var WebhookPaymentService
     */
    private WebhookPaymentService $webhookPaymentService;

    /**
     * Controller constructor method
     *
     * @param WebhookPaymentService $webhookPaymentService
     * @return void
     */
    public function __construct(WebhookPaymentService $webhookPaymentService)
    {
        $this->webhookPaymentService = $webhookPaymentService;
    }

    /**
     * Handle the incoming request.
     *
     * @param WebhookRequest $request
     * @return void
     * @throws ApiException
     */
    public function __invoke(WebhookRequest $request): void
    {
        $payment = $request->getPayment();
        $this->webhookPaymentService->handle($payment);
    }
}
