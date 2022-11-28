<?php

namespace App\Repositories\Customer;

use App\Models\Address\Address;
use App\Models\Company\Company;
use App\Models\Customer\Customer;
use App\Repositories\Base\BaseRepository;
use Illuminate\Database\QueryException;

class CustomerRepository extends BaseRepository
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
     * Save to create or update customer
     *
     * @param array $customerData
     * @return Customer|null
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
     * @param bool $force
     * @return bool
     */
    public function delete(bool $force = false)
    {
        try {
            $customer = $this->getModel();
            $force ? $customer->forceDelete() : $customer->delete();
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
     * @return Customer|null
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

    /**
     * get avaible cities
     *
     * @param Company $company
     * @return array
     */
    public function cities(Company $company): array
    {
        try {
            $customers = Customer::where('company_id', $company->id)->get();
            $address = Address::whereIn('addressable_id', $customers->pluck('id')->toArray())
                ->select('city')
                ->groupBy('city')
                ->get('city')->pluck('city')->toArray();
            $this->setSuccess('Success to populate customer addresses');
            return $address;
        } catch (QueryException $qe) {
            $error = $qe->getMessage();
            $this->setError('Failed to populate customer cities', $error);
        }
    }
}
