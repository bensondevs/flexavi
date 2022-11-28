<?php

namespace App\Observers;

use App\Models\Company\MollieCompanyMandate;

class MollieCompanyMandateObserver
{
    /**
     * Handle the MollieCompanyMandate "creating" event.
     *
     * @param MollieCompanyMandate $mollieCompanyMandate
     * @return void
     */
    public function creating(MollieCompanyMandate $mollieCompanyMandate)
    {
        $mollieCompanyMandate->id = generateUuid();
    }

    /**
     * Handle the MollieCompanyMandate "created" event.
     *
     * @param MollieCompanyMandate $mollieCompanyMandate
     * @return void
     */
    public function created(MollieCompanyMandate $mollieCompanyMandate)
    {
        //
    }

    /**
     * Handle the MollieCompanyMandate "updated" event.
     *
     * @param MollieCompanyMandate $mollieCompanyMandate
     * @return void
     */
    public function updated(MollieCompanyMandate $mollieCompanyMandate)
    {
        //
    }

    /**
     * Handle the MollieCompanyMandate "deleted" event.
     *
     * @param MollieCompanyMandate $mollieCompanyMandate
     * @return void
     */
    public function deleted(MollieCompanyMandate $mollieCompanyMandate)
    {
        //
    }

    /**
     * Handle the MollieCompanyMandate "restored" event.
     *
     * @param MollieCompanyMandate $mollieCompanyMandate
     * @return void
     */
    public function restored(MollieCompanyMandate $mollieCompanyMandate)
    {
        //
    }

    /**
     * Handle the MollieCompanyMandate "force deleted" event.
     *
     * @param MollieCompanyMandate $mollieCompanyMandate
     * @return void
     */
    public function forceDeleted(MollieCompanyMandate $mollieCompanyMandate)
    {
        //
    }
}
