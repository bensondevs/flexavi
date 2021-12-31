<?php

namespace App\Traits;

use App\Models\{
    Address, User, Company, 
    Employee, Customer, Owner
};

trait AddressableRequest 
{
    /**
     * Found address model container
     * 
     * @var \App\Models\Address|null
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
     * @var \App\Models\User|null
     */
    private $user;

    /**
     * Found owner as addressable model container
     * 
     * @var \App\Models\Owner|null
     */
    private $owner;

    /**
     * Found company as addressable model container
     * 
     * @var \App\Models\Company|null
     */
    private $company;

    /**
     * Found employee as addressable model container
     * 
     * @var \App\Models\Employee|null
     */
    private $employee;

    /**
     * Found customer as addressable model container
     * 
     * @var \App\Models\Customer|null
     */
    private $customer;

    /**
     * Get address by supplied input of `address_id` or `id`
     * 
     * @return \App\Models\Address|abort 404
     */
    public function getAddress()
    {
        if ($this->address) return $this->address;

        $id = $this->input('address_id') ?: $this->input('id');
        return $this->address = Address::findOrFail($id);
    }

    /**
     * Get addressable by various type of input
     * 
     * @return mixed|abort 404
     */
    public function getAddressable()
    {
        if ($this->addressable) return $this->addressable;

        switch (true) {
            /**
             * Primitive addressable request
             */
            case $this->has('addressable_id') and $this->has('addressable_type'):
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
                $this->company = $user->company;
                $this->addressable = $this->company;
                break;
        }

        return $this->addressable;
    }

    /**
     * Get owner by supplied input of `owner_id`
     * 
     * @return \App\Models\Owner|abort 404
     */
    public function getOwner()
    {
        if ($this->owner) return $this->owner;

        $id = $this->input('owner_id');
        return $this->owner = Owner::findOrFail($id);
    }

    /**
     * Get owner's company by supplied input of `company_id`
     * 
     * @return \App\Models\Company|abort 404
     */
    public function getOwnerCompany()
    {
        if ($this->company) return $this->company;

        $id = $this->input('company_id');
        return $this->company = Company::findOrFail($id);
    }

    /**
     * Get employee by supplied input of `employee_id`
     * 
     * @return \App\Models\Employee|abort 404
     */
    public function getEmployee()
    {
        if ($this->employee) return $this->employee;

        $id = $this->input('employee_id');
        return $this->employee = Employee::findOrFail($id);
    }

    /**
     * Get customer by supplied input of `customer_id`
     * 
     * @return \App\Models\Customer|abort 404
     */
    public function getCustomer()
    {
        if ($this->customer) return $this->customer;

        $id = $this->input('customer_id');
        return $this->customer = Customer::findOrFail($id);
    }
}