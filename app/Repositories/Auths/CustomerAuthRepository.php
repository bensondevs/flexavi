<?php

namespace App\Repositories\Auths;

use App\Models\Customer\Customer;
use App\Repositories\Base\BaseRepository;
use Illuminate\Database\QueryException;

class CustomerAuthRepository extends BaseRepository
{
    /**
     * Repository constructor method
     *
     * @return void
     */
    public function __construct()
    {
        $this->setInitModel(new Customer());
    }

    /**
     * Attemp customer login
     *
     * @param array  $credentials
     * @return Customer|null
     */
    public function login(array $credentials)
    {
        try {
            $customer = Customer::findUsingCredentials($credentials);
            if (
                !($customer = $customer->attemptAuthenticate(
                    $credentials['unique_key']
                ))
            ) {
                $this->setUnprocessedInput(
                    'Failed to logging in, the unique key does not match out record.'
                );
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
     * @return Customer|null
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
            $this->setError('Failed to reset unique key.');
        }

        return $this->getModel();
    }
}
