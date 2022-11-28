<?php

namespace App\Repositories\Address;

use App\Models\Address\Address;
use App\Repositories\Base\BaseRepository;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\QueryException;

class AddressRepository extends BaseRepository
{
    /**
     * Addressable model container
     *
     * @var Model|null
     */
    private $addressable;

    /**
     * Repository constructor method
     *
     * @return void
     */
    public function __construct()
    {
        $this->setInitModel(new Address());
    }

    /**
     * Save address and attach to a addressable model
     *
     * @param array $addressData
     * @return Address|null
     */
    public function save(array $addressData = []): ?Address
    {
        try {
            $addressable = $this->getAddressable()->fresh();
            $address = $this->getModel() ?? new Address();
            $address->fill($addressData);
            $address->addressable_type = get_class($addressable);
            $address->addressable_id = $addressable->id;
            $address->save();
            $this->setModel($address);
            $this->setSuccess('Successfully save address.');
        } catch (QueryException $qe) {
            $error = $qe->getMessage();
            $this->setError('Failed to save address.', $error);
        }

        return $this->getModel();
    }

    /**
     * Get addressable model
     *
     * @return mixed
     */
    public function getAddressable()
    {
        if ($this->addressable) {
            return $this->addressable;
        }
        if ($this->getModel()->exists) {
            return $this->addressable = $this->getModel()->addressable;
        }

        return null;
    }

    /**
     * Set addressable model
     *
     * @param mixed $addressable
     * @return void
     */
    public function setAddressable($addressable)
    {
        $this->addressable = $addressable;
    }

    /**
     * Delete address
     *
     * @param bool $force set to true to do force delete
     * @return bool
     */
    public function delete(bool $force = false)
    {
        try {
            $address = $this->getModel();
            $force ? $address->forceDelete() : $address->delete();
            $this->destroyModel();
            $this->setSuccess('Successfully delete address.');
        } catch (QueryException $qe) {
            $error = $qe->getMessage();
            $this->setError('Failed to delete address.', $error);
        }

        return $this->returnResponse();
    }

    /**
     * Restore address
     *
     * @return Address|null
     */
    public function restore()
    {
        try {
            $address = $this->getModel();
            $address->restore();
            $this->setModel($address);
            $this->setSuccess('Successfully restore address.');
        } catch (QueryException $qe) {
            $error = $qe->getMessage();
            $this->setError('Failed to restore address.', $error);
        }

        return $this->getModel();
    }
}
