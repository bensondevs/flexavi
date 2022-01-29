<?php

namespace App\Repositories;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\QueryException;
use App\Repositories\Base\BaseRepository;

use App\Models\{ Subscription, SubscriptionPayment };

class SubscriptionRepository extends BaseRepository
{
	/**
	 * Repository constructor method
	 * 
	 * @return void
	 */
	public function __construct()
	{
		$this->setInitModel(new Subscription);
	}

	/**
	 * Purchase subscription
	 * 
	 * @param  array  $subscriptionData
	 * @return \App\Models\Subscription
	 */
	public function purchase(array $subscriptionData)
	{
		try {
			// Create subscription
			$subscription = Subscription::create($subscriptionData);

			// Create subscription payment
			$paymentMethod = $subscriptionData['payment_method'];
			$subscription->createPayment($paymentMethod);

			$this->setModel($subscription);

			$this->setSuccess('Successfully put a subscription plan to billing.');
		} catch (QueryException $qe) {
			$error = $qe->getMessage();
			$this->setError('Failed to purchase subscription', $error);
		}

		return $this->getModel();
	}

	/**
	 * Renew subscription
	 * 
	 * @return \App\Models\Subscription
	 */
	public function renew()
	{
		try {
			$subscription = $this->getModel();
			$renewal = $subscription->renew();

			$this->setModel($renewal);

			$this->setSuccess('Successfully create renewal subscription billing.');
		} catch (QueryException $qe) {
			$error = $qe->getMessage();
			$this->setError('Failed to create renewal subscription billing.', $error);
		}

		return $this->getModel();
	}

	/**
	 * Activate subscription
	 * 
	 * @return \App\Models\Subscription
	 */
	public function activate()
	{
		try {
			$subscription = $this->getModel();

			$subscription->start()
			$subscription->setActivated();

			$this->setModel($subscription);

			$this->setSuccess('Successfully activate a subscription');
		} catch (QueryException $qe) {
			$error = $qe->getMessage();
			$this->setError('Failed to activate subscription.', $error);
		}

		return $this->getModel();
	}

	/**
	 * Terminate subscription
	 * 
	 * @return \App\Models\Subscription
	 */
	public function terminate()
	{
		try {
			$subscription = $this->getModel();

			$subscription->terminate();
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
