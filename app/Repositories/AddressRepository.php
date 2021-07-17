<?php

namespace App\Repositories;

use \Illuminate\Support\Facades\DB;
use \Illuminate\Database\QueryException;

use App\Repositories\Base\BaseRepository;

use App\Models\Address;

class AddressRepository extends BaseRepository
{
	public function __construct()
	{
		$this->setInitModel(new Address);
	}

	public function save(array $addressData = [])
	{
		try {
			$address = $this->getModel();
			$address->fill($addressData);
			$address->save();

			$this->setModel($address);

			$this->setSuccess('Successfully save address.');
		} catch (QueryException $qe) {
			$error = $qe->getMessage();
			$this->setError('Failed to save address.', $error);
		}

		return $this->getModel();
	}
}
