<?php

namespace App\Repositories;

use \Illuminate\Support\Facades\DB;
use \Illuminate\Database\QueryException;

use App\Repositories\Base\BaseRepository;

use App\Models\Address;

class AddressRepository extends BaseRepository
{
	private $addressable;

	public function __construct()
	{
		$this->setInitModel(new Address);
	}

	public function setAddressable($addressable)
	{
		$this->addressable = $addressable;
	}

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

	public function save(array $addressData = [])
	{
		try {
			$addressable = $this->getAddressable();

			$address = $this->getModel();
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
