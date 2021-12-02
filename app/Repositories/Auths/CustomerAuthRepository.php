<?php

namespace App\Repositories\Auths;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\QueryException;
use App\Repositories\Base\BaseRepository;

use App\Models\Customer;

class CustomerAuthRepository extends BaseRepository
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
	 * Attemp customer login
	 * 
	 * @param array  $credentials
	 * @return \App\Models\Customer
	 */
	public function login(array $credentials)
	{
		try {
			if (! $customer = Customer::findUsingCredentials($credentials)) {
				return $this->setNotFound('Customer account not found.');
			}

			if (! $customer = $customer->attemptAutenticate($credentials['unique_key'])) {
				return $this->setUnprocessedInput('Failed to logging in, the unique key does not match out record.');
			}

			$this->setModel($customer);

			$this->setSuccess('Successfully logged in as customer');
		} catch (QueryException $qe) {
			$error = $qe->getMessage();
			$this->setError('Failed to login to customer.', $error);
		}

		return $this->getModel();
	}

	/**
	 * Reset unique key of the customer
	 * 
	 * @return \App\Models\Customer
	 */
	public function resetUniqueKey()
	{
		try {
			$customer = $this->getModel();
			$customer->generateUniqueKey();
			$customer->save();

			$this->setModel($customer);

			$this->setSuccess('Successfully reset unique key.');
		} catch (QueryException $qe) {
			$error = $qe->getMessage();
			$this->setError('Failed to reset unique key.');
		}

		return $this->getModel();
	}
}
