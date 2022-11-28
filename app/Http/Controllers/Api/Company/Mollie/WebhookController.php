<?php

namespace App\Http\Controllers\Api\Company\Mollie;

use Exception;
use Illuminate\Http\Request;
use Laravel\Cashier\Order\Order;
use Mollie\Api\Resources\Payment;
use Mollie\Api\Types\PaymentStatus;

class WebhookController extends BaseWebhookController
{
    /**
     * @throws Exception
     */
    public function handleWebhook(Request $request)
    {
        $payment = $this->getPayment($request->input('id'));

        if (!$payment) {
            return new \Response(null, 200);
        }

        $order = $this->getOrder($payment);
        if ($order && $order->mollie_payment_status !== $payment->status) {
            switch ($payment->status) {
                case PaymentStatus::STATUS_PAID:
                    $order->handlePaymentPaid($payment);
                    break;
                case PaymentStatus::STATUS_FAILED:
                    $order->handlePaymentFailed($payment);
                    break;
                default:
                    break;
            }
        }
    }

    /**
     * @param Payment $payment
     * @return Order|null
     */
    protected function getOrder(Payment $payment)
    {
        $order = Order::findByPaymentId($payment->id);

        if (!$order && isset($payment->metadata, $payment->metadata->temporary_mollie_payment_id)) {
            $order = Order::findByPaymentId($payment->metadata->temporary_mollie_payment_id);

            if ($order) {
                // Store the definite payment id.
                $order->update(['mollie_payment_id' => $payment->id]);
            }
        }

        return $order;
    }
}
