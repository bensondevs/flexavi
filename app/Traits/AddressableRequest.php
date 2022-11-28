<?php

namespace App\Traits;

use App\Models\{Address\Address, Company\Company, Customer\Customer, Employee\Employee, Owner\Owner};

trait AddressableRequest
{
    /**
     * Found address model container
     *
     * @var Address|null
     */
    private $address;

    /**
     * Found addressable model container
     *
     * @var mixed|null
     */
    private $addressable;

    /**
     * Found user as addressable model container
     *
     * @var User|null
     */
    private $user;

    /**
     * Found owner as addressable model container
     *
     * @var Owner|null
     */
    private $owner;

    /**
     * Found company as addressable model container
     *
     * @var Company|null
     */
    private $addressCompany;

    /**
     * Found employee as addressable model container
     *
     * @var Employee|null
     */
    private $employee;

    /**
     * Found customer as addressable model container
     *
     * @var Customer|null
     */
    private $customer;

    /**
     * Get address by supplied input of `address_id` or `id`
     *
     * @return Address|abort 404
     */
    public function getAddress()
    {
        if ($this->address) {
            return $this->address;
        }
        $id = $this->input('address_id') ?: $this->input('id');

        return $this->address = Address::findOrFail($id);
    }

    /**
     * Get addressable by various type of input
     *
     * @return mixed
     */
    public function getAddressable()
    {
        if ($this->addressable) {
            return $this->addressable;
        }
        switch (true) {
            /**
             * Primitive addressable request
             */
            case $this->has('addressable_id') and
                $this->has('addressable_type'):
                $id = $this->input('addressable_id');
                $type = $this->input('addressable_type');
                $this->addressable = (new $type())->findOrFail($id);
                break;

            /**
             * Owner addressable request
             */
            case $this->has('owner_id'):
                $this->addressable = $this->getOwner();
                break;

            /**
             * Company addressable request
             */
            case $this->has('company_id'):
                $this->addressable = $this->getOwnerCompany();
                break;

            /**
             * Employee addressable request
             */
            case $this->has('employee_id'):
                $this->addressable = $this->getEmployee();
                break;

            /**
             * Customer addressable request
             */
            case $this->has('customer_id'):
                $this->addressable = $this->getCustomer();
                break;

            /**
             * Company as default addressable
             */
            default:
                $user = $this->user();
                $this->addressCompany = $user->company;
                $this->addressable = $this->addressCompany;
                break;
        }

        return $this->addressable;
    }

    /**
     * Get owner by supplied input of `owner_id`
     *
     * @return Owner
     */
    public function getOwner()
    {
        if ($this->owner) {
            return $this->owner;
        }
        $id = $this->input('owner_id');

        return $this->owner = Owner::findOrFail($id);
    }

    /**
     * Get owner's company by supplied input of `company_id`
     *
     * @return Company
     */
    public function getOwnerCompany()
    {
        if ($this->addressCompany) {
            return $this->addressCompany;
        }
        $id = $this->input('company_id');

        return $this->addressCompany = Company::findOrFail($id);
    }

    /**
     * Get employee by supplied input of `employee_id`
     *
     * @return Employee
     */
    public function getEmployee()
    {
        if ($this->employee) {
            return $this->employee;
        }
        $id = $this->input('employee_id');

        return $this->employee = Employee::findOrFail($id);
    }

    /**
     * Get customer by supplied input of `customer_id`
     *
     * @return Customer
     */
    public function getCustomer()
    {
        if ($this->customer) {
            return $this->customer;
        }
        $id = $this->input('customer_id');

        return $this->customer = Customer::findOrFail($id);
    }
}
