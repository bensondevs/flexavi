<?php

namespace App\Observers;

use App\Models\Customer;

use App\Mail\Customer\CustomerRegistered;
use App\Mail\Customer\NewUniqueKeyGenerated;

use App\Jobs\SendMail;

class CustomerObserver
{
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
