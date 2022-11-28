<?php

namespace App\Repositories\PaymentPickup;

use App\Models\{PaymentPickup\PaymentPickup, PaymentPickup\PaymentPickupable};
use App\Repositories\Base\BaseRepository;
use Illuminate\Database\QueryException;

class PaymentPickupRepository extends BaseRepository
{
    /**
     * Create New Repository Instance
     *
     * @return void
     */
    public function __construct()
    {
        $this->setInitModel(new PaymentPickup());
    }

    /**
     * Save or update payment pickup
     *
     * @param array  $data
     * @return PaymentPickup|null
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
     * @return PaymentPickup|null
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
            $this->setError(
                'Failed to add pickupable to payment pickup.',
                $error
            );
        }

        return $this->getModel();
    }

    /**
     * Add multiple pickupables to payment pickup
     *
     * @param array  $pickupables
     * @return PaymentPickup|null
     */
    public function addMultiplePickupables(array $pickupables)
    {
        try {
            $paymentPickup = $this->getModel();
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
     * @return PaymentPickup|null
     */
    public function removePickupable($pickupable)
    {
        try {
            $pickup = $this->getModel();
            $type = get_class($pickupable);
            if ($type !== PaymentPickupable::class) {
                $paymentPickupable = PaymentPickupable::wherePaymentPickup(
                    $pickup
                )
                    ->wherePickupable($pickupable)
                    ->firstOrFail();
            } else {
                $paymentPickupable = $pickupable;
            }
            $paymentPickupable->delete();
            $this->setModel($pickup);
            $this->setSuccess('Successfully remove payment pickupable.');
        } catch (QueryException $qe) {
            $error = $qe->getMessage();
            $this->setError('Failed to remove pickupable.', $error);
        }

        return $this->getModel();
    }

    /**
     * Remove multiple pickupables
     *
     * @param array  $pickupables
     * @return PaymentPickup|null
     */
    public function removeMultiplePickupables(array $pickupables)
    {
        try {
            $pickup = $this->getModel();
            $ids = array_map(function ($pickupable) {
                if (isset($pickupable['id'])) {
                    return $pickupable['id'];
                }
            }, $pickupables);
            PaymentPickupable::whereIn(
                'payment_pickupable_id',
                $ids
            )->destroy();
            $this->setModel($pickup);
            $this->setSuccess('Successfully remove multiple pickupable.');
        } catch (QueryException $qe) {
            $error = $qe->getMessage();
            $this->setError('Failed to remove multiple pickupables.', $error);
        }

        return $this->getModel();
    }

    /**
     * Truncate payment pickup's pickupables
     *
     * @return PaymentPickup|null
     */
    public function truncatePickupables()
    {
        try {
            $pickup = $this->getModel();
            $pickup->pickupables()->delete();
            $this->setModel($pickup);
            $this->setSuccess('Successfully remove all pickupable.');
        } catch (QueryException $qe) {
            $error = $qe->getMessage();
            $this->setError('Failed to remove all pickupables.', $error);
        }

        return $this->getModel();
    }

    /**
     * Delete payment pickup
     *
     * @param bool  $force
     * @return bool
     */
    public function delete(bool $force = false)
    {
        try {
            $pickup = $this->getModel();
            $force ? $pickup->forceDelete() : $pickup->delete();
            $this->destroyModel();
            $this->setSuccess('Successfully delete payment pickup.');
        } catch (QueryException $qe) {
            $error = $qe->getMessage();
            $this->setError('Failed to delete payment pickup.', $error);
        }

        return $this->returnResponse();
    }

    /**
     * Restore payment pickup
     *
     * @return PaymentPickup|null
     */
    public function restore()
    {
        try {
            $pickup = $this->getModel();
            $pickup->restore();
            $this->setModel($pickup);
            $this->setSuccess('Successfully restore payment pickup.');
        } catch (QueryException $qe) {
            $error = $qe->getMessage();
            $this->setError('Failed to restore payment pickup.', $error);
        }

        return $this->getModel();
    }
}
