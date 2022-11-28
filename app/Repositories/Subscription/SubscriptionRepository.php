<?php

namespace App\Repositories\Subscription;

use App\Enums\Subscription\SubscriptionStatus;
use App\Models\Company\Company;
use App\Models\Subscription\Subscription;
use App\Repositories\Base\BaseRepository;
use Illuminate\Database\QueryException;
use Illuminate\Support\Collection;

class SubscriptionRepository extends BaseRepository
{
    /**
     * Repository constructor method
     *
     * @return void
     */
    public function __construct()
    {
        $this->setInitModel(new Subscription());
    }

    /**
     * Get active subscriptions whose now is last day of period
     *
     * @return Collection
     */
    public function activeSubscriptionsWhoseNowIsLastDayOfPeriod(): Collection
    {
        return $this->getModel()
            ->active()
            ->whereDate('subscription_end', now()->format('Y-m-d'))
            ->get();
    }

    /**
     * Get active subscriptions whose validity period has expired
     *
     * @return Collection
     */
    public function activeSubscriptionsWhosePeriodHasExpired(): Collection
    {
        return $this->getModel()
            ->active()
            ->whereDate('subscription_end', '<=', now()->format('Y-m-d'))
            ->get();
    }

    /**
     * Get active subscriptions that will expire in the next 3 days
     *
     * @return Collection
     */
    public function activeSubscriptionsWillExpireInThreeDays(): Collection
    {
        return $this->getModel()
            ->active()
            ->whereDate('subscription_end', '<=', now()->addDays(3)->format('Y-m-d'))
            ->get();
    }

    /**
     * Get trial subscription of company
     *
     * @param Company $company
     * @return Subscription|null
     */
    public function companyTrialSubscription(Company $company): ?Subscription
    {
        return $this->getModel()
            ->forCompany($company)
            ->trial()
            ->first();
    }

    /**
     * Purchase subscription
     *
     * @param array $subscriptionData
     * @return Subscription|null
     */
    public function purchase(array $subscriptionData): ?Subscription
    {
        try {
            $subscription = $this->getModel();
            $subscription->fill($subscriptionData);
            $subscription->save();

            $this->setModel($subscription);
            app(SubscriptionPaymentRepository::class)->createPayment($subscription);
            $this->setSuccess('Successfully put a subscription plan to billing.');
        } catch (QueryException $qe) {
            $error = $qe->getMessage();
            $this->setError('Failed to purchase subscription', $error);
        }

        return $this->getModel();
    }

    /**
     * Start trial subscription
     *
     * @param array $subscriptionData
     * @return Subscription|null
     */
    public function startTrial(array $subscriptionData): ?Subscription
    {
        try {
            $subscription = $this->getModel();
            $subscription->fill($subscriptionData);
            $subscription->save();
            $this->setModel($subscription);
            app(SubscriptionPaymentRepository::class)->createTrialPayment($subscription);
            $this->setSuccess('Successfully started a trial subscription.');
        } catch (QueryException $qe) {
            $error = $qe->getMessage();
            $this->setError('Failed to started a trial subscription.', $error);
        }
        return $this->getModel();
    }

    /**
     * Renew subscription
     *
     * @return Subscription|null
     */
    public function start(): ?Subscription
    {
        try {
            $subscription = $this->getModel();
            $subscription->start();
            $this->setModel($subscription);
            $this->setSuccess(
                'Successfully to start subscription.'
            );
        } catch (QueryException $qe) {
            $error = $qe->getMessage();
            $this->setError(
                'Failed to start subscription.',
                $error
            );
        }

        return $this->getModel();
    }

    /**
     * Renew subscription
     *
     * @return Subscription|null
     */
    public function renew(): ?Subscription
    {
        try {
            $subscription = $this->getModel();
            $renewal = $subscription->renew();
            $this->setModel($renewal);
            $this->setSuccess(
                'Successfully create renewal subscription billing.'
            );
        } catch (QueryException $qe) {
            $error = $qe->getMessage();
            $this->setError(
                'Failed to create renewal subscription billing.',
                $error
            );
        }

        return $this->getModel();
    }

    /**
     * Activate subscription
     *
     * @return Subscription|null
     */
    public function activate(): ?Subscription
    {
        try {
            $subscription = $this->getModel();
            $subscription->setActive();
            $this->setModel($subscription);
            $this->setSuccess('Successfully activate a subscription');
        } catch (QueryException $qe) {
            $error = $qe->getMessage();
            $this->setError('Failed to activate subscription.', $error);
        }

        return $this->getModel();
    }

    /**
     * Set inactive subscription
     *
     * @return Subscription|null
     */
    public function inactive(): ?Subscription
    {
        try {
            $subscription = $this->getModel();
            $subscription->status = SubscriptionStatus::Inactive;
            $subscription->save();
            $this->setModel($subscription);
            $this->setSuccess('Successfully set inactive a subscription');
        } catch (QueryException $qe) {
            $error = $qe->getMessage();
            $this->setError('Failed to set inactive subscription.', $error);
        }

        return $this->getModel();
    }

    /**
     * Terminate subscription
     *
     * @return Subscription|null
     */
    public function terminate(): ?Subscription
    {
        try {
            $subscription = $this->getModel();
            $subscription->setTerminated();
            $this->setModel($subscription);
            $this->setSuccess('Successfully terminate a subscription.');
        } catch (QueryException $qe) {
            $error = $qe->getMessage();
            $this->setError('Failed to terminate subscription.', $error);
        }

        return $this->getModel();
    }
}
