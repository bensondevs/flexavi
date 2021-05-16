<?php

namespace App\Repositories;

use \Illuminate\Support\Facades\DB;
use \Illuminate\Database\QueryException;

use App\Models\Customer;

use App\Repositories\Base\BaseRepository;

class CustomerRepository extends BaseRepository
{
	public function __construct()
	{
		$this->setInitModel(new Customer);
	}

	public function save(array $customerData)
	{
		try {
			$customer = $this->getModel();
			$customer->fill($customerData);
			$customer->save();

			$this->setModel($customer);

			$this->setSuccess('Successfully save customer data.');			
		} catch (QueryException $qe) {
			$this->setError(
				'Failed to save customer data.',
				$qe->getMessage()
			);
		}

		return $this->getModel();
	}

	public function delete($force = false)
	{
		try {
			$customer = $this->getModel();
			$force ?
				$customer->forceDelete() :
				$customer->delete();
			$this->destroyModel();

			$this->setSuccess('Successully delete customer data.');
		} catch (QueryException $qe) {
			$this->setError(
				'Failed to delete customer data.',
				$qe->getMessage()
			);
		}

		return $this->returnResponse();
	}
}
