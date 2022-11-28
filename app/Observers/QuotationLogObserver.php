<?php

namespace App\Observers;

use App\Models\Quotation\QuotationLog;

class QuotationLogObserver
{
    /**
     * Handle the QuotationLog "creating" event.
     *
     * @param QuotationLog $quotationLog
     * @return void
     */
    public function creating(QuotationLog $quotationLog): void
    {
        $quotationLog->id = generateUuid();
        $quotationLog->properties = json_encode($quotationLog->properties);
    }

    /**
     * Handle the QuotationLog "created" event.
     *
     * @param QuotationLog $quotationLog
     * @return void
     */
    public function created(QuotationLog $quotationLog): void
    {
        //
    }

    /**
     * Handle the QuotationLog "updated" event.
     *
     * @param QuotationLog $quotationLog
     * @return void
     */
    public function updated(QuotationLog $quotationLog): void
    {
        //
    }

    /**
     * Handle the QuotationLog "deleted" event.
     *
     * @param QuotationLog $quotationLog
     * @return void
     */
    public function deleted(QuotationLog $quotationLog): void
    {
        //
    }

    /**
     * Handle the QuotationLog "restored" event.
     *
     * @param QuotationLog $quotationLog
     * @return void
     */
    public function restored(QuotationLog $quotationLog): void
    {
        //
    }

    /**
     * Handle the QuotationLog "force deleted" event.
     *
     * @param QuotationLog $quotationLog
     * @return void
     */
    public function forceDeleted(QuotationLog $quotationLog): void
    {
        //
    }
}
