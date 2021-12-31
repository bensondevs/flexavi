<?php

namespace App\Observers;

use App\Jobs\SendMail;
use App\Models\Customer;
use App\Mail\Customer\{ CustomerRegistered, NewUniqueKeyGenerated };

class CustomerObserver
{
    /**
     * Handle the Customer "creating" event.
     *
     * @param  \App\Models\Customer  $customer
     * @return void
     */
    public function creating(Customer $customer)
    {
        $customer->id = generateUuid();
        $customer->unique_key = $customer->generateUniqueKey();
    }

    /**
     * Handle the Customer "created" event.
     *
     * @param  \App\Models\Customer  $customer
     * @return void
     */
    public function created(Customer $customer)
    {
        /*if ($email = $customer->email) {
            $mail = new CustomerRegistered($customer);

            $sendMailJob = new SendMail($mail, $email);
            $sendMailJob->delay(1);
            dispatch($sendMailJob);
        }*/
    }

    /**
     * Handle the Customer "updated" event.
     *
     * @param  \App\Models\Customer  $customer
     * @return void
     */
    public function updated(Customer $customer)
    {
        /*if ($email = $customer->email) {
            if ($customer->isDirty('unique_key')) {
                $mail = new NewUniqueKeyGenerated($customer);

                $sendMailJob = new SendMail($mail, $email);
                $sendMailJob->delay(1);
                dispatch($sendMailJob);
            }
        }*/
    }

    /**
     * Handle the Customer "deleted" event.
     *
     * @param  \App\Models\Customer  $customer
     * @return void
     */
    public function deleted(Customer $customer)
    {
        //
    }

    /**
     * Handle the Customer "restored" event.
     *
     * @param  \App\Models\Customer  $customer
     * @return void
     */
    public function restored(Customer $customer)
    {
        //
    }

    /**
     * Handle the Customer "force deleted" event.
     *
     * @param  \App\Models\Customer  $customer
     * @return void
     */
    public function forceDeleted(Customer $customer)
    {
        //
    }
}
