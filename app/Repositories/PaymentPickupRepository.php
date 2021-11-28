<?php

namespace App\Repositories;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\QueryException;
use App\Repositories\Base\BaseRepository;

use App\Models\PaymentPickup;

class PaymentPickupRepository extends BaseRepository
{
	/**
	 * Create New Repository Instance
	 * 
	 * @return void
	 */
	public function __construct()
	{
		$this->setInitModel(new PaymentPickup);
	}

	/**
	 * Save or update payment pickup
	 * 
	 * @param array  $data
	 * @return \App\Models\PaymentPickup
	 */
	public function save(array $data = [])
	{
		try {
			$paymentPickup = $this->getModel();
			$paymentPickup->fill($data);
			$paymentPickup->save();

			$this->setModel($paymentPickup);

			$this->setSuccess('Successfully save payment pickup data.');
		} catch (QueryException $qe) {
			$error = $qe->getMessage();
			$this->setError('Failed to save payment pickup data.', $error);
		}

		return $this->getModel();
	}

	/**
	 * Add pickupable to be picked up
	 * 
	 * @param mixed  $pickupable
	 * @return \App\Models\PaymentPickup
	 */
	public function addPickupable($pickupable)
	{
		try {
			$paymentPickup = $this->getModel();
			$paymentPickup->pickupables()->attach($pickupable);

			$this->setModel($paymentPickup);

			$this->setSuccess('Successfully add pickupable to payment pickup');
		} catch (QueryException $qe) {
			$error = $qe->getMessage();
			$this->setError('Failed to add pickupable to payment pickup.', $error);
		}

		return $this->getModel();
	}

	/**
	 * Add multiple pickupables to payment pickup
	 * 
	 * @param array  $pickupables
	 * @return \App\Models\PaymentPickup
	 */
	public function addMultiplePickupables(array $pickupables)
	{
		try {
			$paymentPickup = $this->getModel();
			
			// Insert pivots for payment pickupables
			$rawMorphPivots = [];
			foreach ($pickupables as $pickupable) {
				array_push($rawMorphPivots, [
					'id' => generateUuid(),
					'payment_pickup_id' => $paymentPickup->id,
					'payment_pickupable_type' => get_class($pickupable),
					'payment_pickupable_id' => $pickupable->id,
					'created_at' => now(),
					'updated_at' => now(),
				]);
			}
			PaymentPickupable::insert($rawMorphPivots);

			// Load payment pickup pivots
			$paymentPickup->load(['pickupables']);

			$this->setModel($paymentPickup);

			$this->setSuccess('Successfully add multiple pickupables.');
		} catch (QueryException $qe) {
			$error = $qe->getMessage();
			$this->setError('Failed to add multiple pickupables.', $error);
		}

		return $this->getModel();
	}

	/**
	 * Remove pickupable from payment pickup
	 * 
	 * @param mixed  $pickupable
	 * @return \App\Models\PaymentPickup
	 */
	public function removePickupable($pickupable)
	{
		//
	}

	/**
	 * Remove multiple pickupables
	 * 
	 * @param array  $pickupables
	 * @return \App\Models\PaymentPickup
	 */
	public function removeMultiplePickupables()
	{
		//
	}

	/**
	 * Truncate payment pickup's pickupables
	 * 
	 * @return \App\Models\PaymentPickup 
	 */
	public function truncatePickupables()
	{
		//
	}

	/**
	 * Delete payment pickup
	 * 
	 * @param bool  $force
	 * @return bool
	 */
	public function delete(bool $force = false)
	{
		//
	}
}
