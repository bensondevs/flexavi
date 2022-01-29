<?php

namespace App\Repositories;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\QueryException;
use App\Repositories\Base\BaseRepository;

use App\Models\Customer;

class CustomerRepository extends BaseRepository
{
	/**
	 * Repository constructor method
	 * 
	 * @return void
	 */
	public function __construct()
	{
		$this->setInitModel(new Customer);
	}

	/**
	 * Save to create or update customer
	 * 
	 * @param  array  $customerData
	 * @return \App\Models\Customer
	 */
	public function save(array $customerData)
	{
		try {
			$customer = $this->getModel();
			$customer->fill($customerData);
			$customer->save();

			$this->setModel($customer);

			$this->setSuccess('Successfully save customer data.');			
		} catch (QueryException $qe) {
			$error = $qe->getMessage();
			$this->setError('Failed to save customer data.', $error);
		}

		return $this->getModel();
	}

	/**
	 * Delete customer
	 * 
	 * @param  bool  $force
	 * @return bool
	 */
	public function delete(bool $force = false)
	{
		try {
			$customer = $this->getModel();
			$force ?
				$customer->forceDelete() :
				$customer->delete();
			$this->destroyModel();

			$this->setSuccess('Successully delete customer data.');
		} catch (QueryException $qe) {
			$error = $qe->getMessage();
			$this->setError('Failed to delete customer data.', $error);
		}

		return $this->returnResponse();
	}

	/**
	 * Restore customer
	 * 
	 * @return \App\Models\Customer
	 */
	public function restore()
	{
		try {
			$customer = $this->getModel();
			$customer->restore();

			$this->setModel($customer);

			$this->setSuccess('Successfully restore customer data.');
		} catch (QueryException $qe) {
			$error = $qe->getMessage();
			$this->setError('Failed to restore customer data.', $error);
		}

		return $this->getModel();
	}
}
