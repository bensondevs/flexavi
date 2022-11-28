<?php

namespace App\Repositories\Subscription;

use App\Enums\SubscriptionPayment\MolliePaymentMethod;
use App\Enums\SubscriptionPayment\PaymentGatewayType;
use App\Enums\SubscriptionPayment\SubscriptionPaymentMethod;
use App\Enums\SubscriptionPayment\SubscriptionPaymentStatus;
use App\Models\Subscription\Subscription;
use App\Models\Subscription\SubscriptionPayment;
use App\Repositories\Base\BaseRepository;
use Illuminate\Database\QueryException;

class SubscriptionPaymentRepository extends BaseRepository
{
    /**
     * Repository constructor method
     *
     * @return void
     */
    public function __construct()
    {
        $this->setInitModel(new SubscriptionPayment());
    }

    /**
     * Create subscription payment
     *
     * @param Subscription $subscription
     * @return SubscriptionPayment|null
     */
    public function createPayment(Subscription $subscription): ?SubscriptionPayment
    {
        try {
            $payment = $this->getModel();
            $payment->fill([
                'subscription_id' => $subscription->id,
                'payment_method' => SubscriptionPaymentMethod::PaymentGateway,
                'payment_gateway_type' => PaymentGatewayType::Mollie,
                'payment_gateway_method' => MolliePaymentMethod::CreditCard,
                'amount' => $subscription->subscriptionPlanPeriod->total,
            ]);
            $payment->save();
            $this->setModel($payment);
            $this->setSuccess("Successfully created subscription payment");
        } catch (QueryException $e) {
            $this->setError("Error creating subscription payment");
        }
        return $this->getModel();
    }

    /**
     * Save subscription payment.
     *
     * @param array $data
     * @param bool $saveQuietly
     * @return SubscriptionPayment|null
     */
    public function save(array $data, bool $saveQuietly = false): ?SubscriptionPayment
    {
        try {
            $model = $this->getModel();
            $model->fill($data);
            $saveQuietly ? $model->saveQuietly() : $model->save();
            $this->setModel($model);
            $this->setSuccess("Successfully saved subscription payment");
        } catch (QueryException $e) {
            $this->setError("Error saving subscription payment");
        }
        return $this->getModel();
    }

    /**
     * Create subscription payment
     *
     * @param Subscription $subscription
     * @return SubscriptionPayment|null
     */
    public function createTrialPayment(Subscription $subscription): ?SubscriptionPayment
    {
        try {
            $payment = $this->getModel();
            $payment->fill([
                'company_id' => $subscription->company_id,
                'amount' => 0,
                'subscription_id' => $subscription->id,
                'status' => SubscriptionPaymentStatus::Settled,
                'payment_method' => null,
                'payment_gateway_type' => null,
            ]);
            $payment->save();
            $this->setModel($payment);
            $this->setSuccess("Successfully created trial subscription payment");
        } catch (QueryException $e) {
            $this->setError("Error creating trial subscription payment");
        }
        return $this->getModel();
    }

    /**
     * Update payment status as settled
     *
     * @return SubscriptionPayment|null
     */
    public function settled(): ?SubscriptionPayment
    {
        try {
            $payment = $this->getModel();
            $payment->status = SubscriptionPaymentStatus::Settled;
            $payment->save();
            $this->setModel($payment);
            $this->setSuccess('Successfully update payment.');
        } catch (QueryException $qe) {
            $this->setError(
                'Failed to update subscription payment. ' . $qe->getMessage()
            );
        }
        return $this->getModel();
    }

    /**
     * Update payment status as failed
     *
     * @return SubscriptionPayment|null
     */
    public function failed(): ?SubscriptionPayment
    {
        try {
            $payment = $this->getModel();
            $payment->status = SubscriptionPaymentStatus::Failed;
            $payment->save();
            $this->setModel($payment);
            $this->setSuccess('Successfully update payment.');
        } catch (QueryException $qe) {
            $this->setError(
                'Failed to update subscription payment. ' . $qe->getMessage()
            );
        }
        return $this->getModel();
    }

    /**
     * Update payment status as waiting
     *
     * @return SubscriptionPayment|null
     */
    public function waiting(): ?SubscriptionPayment
    {
        try {
            $payment = $this->getModel();
            $payment->status = SubscriptionPaymentStatus::Waiting;
            $payment->save();
            $this->setModel($payment);
            $this->setSuccess('Successfully update payment.');
        } catch (QueryException $qe) {
            $this->setError(
                'Failed to update subscription payment. ' . $qe->getMessage()
            );
        }
        return $this->getModel();
    }

    /**
     * Update payment status as expired
     *
     * @return SubscriptionPayment|null
     */
    public function expired(): ?SubscriptionPayment
    {
        try {
            $payment = $this->getModel();
            $payment->status = SubscriptionPaymentStatus::Expired;
            $payment->save();
            $this->setModel($payment);
            $this->setSuccess('Successfully update payment.');
        } catch (QueryException $qe) {
            $this->setError(
                'Failed to update subscription payment. ' . $qe->getMessage()
            );
        }
        return $this->getModel();
    }
}
