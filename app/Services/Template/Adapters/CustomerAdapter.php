<?php

namespace App\Services\Template\Adapters;

use App\Models\Customer\Customer;

class CustomerAdapter
{
    /**
     * Get customer fullname
     *
     * @param string $customerId
     * @return string
     */
    public function getCustomerFullName(string $customerId): string
    {
        $customer = Customer::findOrFail($customerId);
        return $customer->fullname;
    }

    /**
     * Get customer email
     *
     * @param string $customerId
     * @return string
     */
    public function getCustomerEmail(string $customerId): string
    {
        $customer = Customer::findOrFail($customerId);
        return $customer->email;
    }

    /**
     * Get customer $this->phone
     *
     * @param string $customerId
     * @return string
     */
    public function getCustomerPhone(string $customerId): string
    {
        $customer = Customer::findOrFail($customerId);
        return $customer->phone;
    }


    /**
     * Get customer acquired through description
     *
     * @param string $customerId
     * @return string
     */
    public function getCustomerAcquiredThroughDescription(string $customerId): string
    {
        $customer = Customer::findOrFail($customerId);
        return $customer->acquired_through_description;
    }

    /**
     * Get customer address
     *
     * @param string $customerId
     * @return string
     */
    public function getCustomerAddress(string $customerId): string
    {
        $customer = Customer::findOrFail($customerId);
        $customerAddress = $customer->address;
        return $customerAddress->address;
    }

    /**
     * Get customer city
     *
     * @param string $customerId
     * @return string
     */
    public function getCustomerCity(string $customerId): string
    {
        $customer = Customer::findOrFail($customerId);
        $customerAddress = $customer->address;
        return $customerAddress->city;
    }

    /**
     * Get customer zip code
     *
     * @param string $customerId
     * @return string
     */
    public function getCustomerZipCode(string $customerId): string
    {
        $customer = Customer::findOrFail($customerId);
        $customerAddress = $customer->address;
        return $customerAddress->zipcode;
    }

    /**
     * Get customer house number
     *
     * @param string $customerId
     * @return string
     */
    public function getCustomerHouseNumber(string $customerId): string
    {
        $customer = Customer::findOrFail($customerId);
        $customerAddress = $customer->address;
        return $customerAddress->house_number;
    }

    /**
     * Get customer province
     *
     * @param string $customerId
     * @return string
     */
    public function getCustomerProvince(string $customerId): string
    {
        $customer = Customer::findOrFail($customerId);
        $customerAddress = $customer->address;
        return $customerAddress->province;
    }

}
