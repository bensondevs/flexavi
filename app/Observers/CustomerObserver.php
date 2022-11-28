<?php

namespace App\Observers;

use App\Models\Customer\Customer;
use App\Services\Log\LogService;

class CustomerObserver
{
    /**
     * Handle the Customer "creating" event.
     *
     * @param Customer $customer
     * @return void
     */
    public function creating(Customer $customer): void
    {
        $customer->id = generateUuid();
        $customer->unique_key = $customer->generateUniqueKey();
        if ((!$customer->acquired_by) and auth()->check()) {
            $customer->acquired_by = auth()->user()->id;
        }
    }

    /**
     * Handle the Customer "created" event.
     *
     * @param Customer $customer
     * @return void
     */
    public function created(Customer $customer): void
    {
        LogService::make('customer.store')
            ->on($customer)
            ->write();
    }

    /**
     * Handle the Customer "updated" event.
     *
     * @param Customer $customer
     * @return void
     */
    public function updating(Customer $customer): void
    {
        session()->put('props.old.customer', $customer->getOriginal());
    }

    /**
     * Handle the Customer "updated" event.
     *
     * @param Customer $customer
     * @return void
     */
    public function updated(Customer $customer): void
    {
        if ($customer->isDirty('fullname')) {
            LogService::make("customer.updates.fullname")
                ->with(
                    "old.subject",
                    session("props.old.customer")
                )
                ->on($customer)->write();
        }

        if ($customer->isDirty('email')) {
            LogService::make("customer.updates.email")
                ->with(
                    "old.subject",
                    session("props.old.customer")
                )
                ->on($customer)->write();
        }

        if ($customer->isDirty('phone')) {
            LogService::make("customer.updates.phone")
                ->with(
                    "old.subject",
                    session("props.old.customer")
                )
                ->on($customer)->write();
        }

        if ($customer->isDirty('second_phone')) {
            LogService::make("customer.updates.second_phone")
                ->with(
                    "old.subject",
                    session("props.old.customer")
                )
                ->on($customer)->write();
        }

        if ($customer->isDirty('acquired_through')) {
            LogService::make("customer.updates.acquired_through")
                ->with(
                    "old.subject",
                    session("props.old.customer")
                )
                ->on($customer)->write();
        }

        session()->forget("props.old.customer");
    }

    /**
     * Handle the Customer "deleted" event.
     *
     * @param Customer $customer
     * @return void
     */
    public function deleted(Customer $customer): void
    {
        LogService::make("customer.delete")
            ->on($customer)
            ->write();
    }

    /**
     * Handle the Customer "restored" event.
     *
     * @param Customer $customer
     * @return void
     */
    public function restored(Customer $customer): void
    {
        LogService::make("customer.restore")
            ->on($customer)
            ->write();
    }

    /**
     * Handle the Customer "force deleted" event.
     *
     * @param Customer $customer
     * @return void
     */
    public function forceDeleted(Customer $customer): void
    {
        LogService::make("customer.force_delete")
            ->on($customer)
            ->write();
    }
}
