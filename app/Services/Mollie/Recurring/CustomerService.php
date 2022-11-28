<?php

namespace App\Services\Mollie\Recurring;

use Mollie\Api\Exceptions\ApiException;
use Mollie\Api\Resources\BaseCollection;
use Mollie\Api\Resources\BaseResource;
use Mollie\Api\Resources\Customer;
use Mollie\Api\Resources\CustomerCollection;
use Mollie\Api\Resources\PaymentCollection;
use Mollie\Laravel\Facades\Mollie;

class CustomerService
{
    /**
     * Get list customer
     *
     * @return BaseCollection|CustomerCollection
     * @throws ApiException
     */
    public function list(): BaseCollection|CustomerCollection
    {
        return Mollie::api()->customers()->page(null, 10, []);
    }

    /**
     * Create or Update Mollie Customer
     *
     * @param $customerable
     * @return BaseResource|Customer
     * @throws ApiException
     */
    public function createOrUpdate($customerable): Customer|BaseResource
    {
        $mollie = Mollie::api()->customers();
        return $customerable->mollie_customer_id ?
            $mollie->update($customerable->mollie_customer_id, [
                'name' => get_class($customerable) == "App\Models\User" ? $customerable->fullname : $customerable->company_name,
                'email' => $customerable->email
            ]) :
            $mollie->create([
                'name' => get_class($customerable) == "App\Models\User" ? $customerable->fullname : $customerable->company_name,
                'email' => $customerable->email,
                'metadata' => [
                    'resource' => get_class($customerable) == "App\Models\User" ? "user" : "company",
                    'type' => get_class($customerable),
                    'id' => $customerable->id,
                ]
            ]);
    }

    /**
     * Find mollie customer
     *
     * @param  $customerable
     * @return BaseResource|Customer
     * @throws ApiException
     */
    public function find($customerable): Customer|BaseResource
    {
        return Mollie::api()->customers()->get($customerable->mollie_customer_id, ['mode' => 'test']);
    }

    /**
     * Delete mollie customer
     *
     * @param $customerable
     * @return null
     * @throws ApiException
     */
    public function delete($customerable)
    {
        return Mollie::api()->customers()->delete($customerable->mollie_customer_id);
    }

    /**
     * Get mollie customer payments
     *
     * @param $customerable
     * @return PaymentCollection
     * @throws ApiException
     */
    public function payments($customerable): PaymentCollection
    {
        return Mollie::api()->customers()->get($customerable->mollie_customer_id)->payments();
    }
}
