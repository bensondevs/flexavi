<?php

namespace App\Traits;

use App\Models\{
    Address, User, Company, Employee, Customer, Owner
};

trait AddressableRequest 
{
    private $address;

    private $addressable;

    private $user;
    private $owner;
    private $company;
    private $employee;
    private $customer;

    public function getAddress()
    {
        if ($this->address) return $this->address;

        $id = $this->input('address_id') ?: $this->input('id');
        return $this->address = Address::findOrFail($id);
    }

    public function getAddressable()
    {
        if ($this->addressable) {
            return $this->addressable;
        }

        if ($this->has('addressable_id') && $this->has('addressable_type')) {
            $id = $this->input('addressable_id');
            $type = $this->input('addressable_type');

            $model = new $type();
            return $this->addressable = $model->findOrFail($id);
        }

        if ($this->has('owner_id')) {
            return $this->addressable = $this->getOwner();
        }

        if ($this->has('company_id')) {
            return $this->addressable = $this->getCompany();
        }

        if ($this->has('employee_id')) {
            return $this->addressable = $this->getEmployee();
        }

        if ($this->has('customer_id')) {
            return $this->addressable = $this->getCustomer();
        }

        $user = auth()->user();
        $this->company = $user->{$user->user_role}->company;
        return $this->addressable = $this->company;
    }

    public function getOwner()
    {
        if ($this->owner) return $this->owner;

        if ($id = $this->input('owner_id')) {
            $this->owner = Owner::findOrFail($id);
            return $this->owner;
        }

        if ($owner = auth()->user()->owner) {
            return $this->owner = $owner;
        }

        return abort(404, 'No owner instance loaded.');
    }

    public function getOwnerCompany()
    {
        if ($this->company) return $this->company;

        $id = $this->input('company_id');
        return $this->company = Company::findOrFail($id);
    }

    public function getEmployee()
    {
        if ($this->employee) return $this->employee;

        $id = $this->input('employee_id');
        return $this->employee = Employee::findOrFail($id);
    }

    public function getCustomer()
    {
        if ($this->customer) return $this->customer;

        $id = $this->input('customer_id');
        return $this->customer = Customer::findOrFail($id);
    }
}