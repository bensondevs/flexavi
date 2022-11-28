<?php

namespace App\Http\Controllers\Api\Company\Mollie;

use Exception;
use Illuminate\Contracts\Container\BindingResolutionException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Event;
use Laravel\Cashier\Events\FirstPaymentFailed;
use Laravel\Cashier\Events\FirstPaymentPaid;
use Laravel\Cashier\FirstPayment\FirstPaymentHandler;
use Laravel\Cashier\Mollie\Contracts\UpdateMolliePayment;
use Mollie\Api\Exceptions\ApiException;
use Symfony\Component\HttpFoundation\Response;

class FirstPaymentWebhookController extends BaseWebhookController
{
    /**
     * @param Request $request
     * @return Response
     *
     * @throws ApiException|BindingResolutionException Only in debug mode
     * @throws Exception
     */
    public function handleWebhook(Request $request)
    {
        $payment = $this->getPayment($request->get('id'));

        if ($payment) {
            if ($payment->isPaid()) {
                $order = (new FirstPaymentHandler($payment))->execute();
                $payment->webhookUrl = route('webhooks.mollie.aftercare');

                /** @var UpdateMolliePayment $updateMolliePayment */
                $updateMolliePayment = app()->make(UpdateMolliePayment::class);
                $payment = $updateMolliePayment->execute($payment);

                Event::dispatch(new FirstPaymentPaid($payment, $order));
            } elseif ($payment->isFailed()) {
                Event::dispatch(new FirstPaymentFailed($payment));
            }
        }

        return new Response(null, 200);
    }
}
