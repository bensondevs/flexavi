<?php

namespace App\Observers;

use App\Models\Quotation\QuotationItem;

class QuotationItemObserver
{
    /**
     * Handle the QuotationItem "creating" event.
     *
     * @param QuotationItem $quotationItem
     * @return void
     */
    public function creating(QuotationItem $quotationItem): void
    {
        $quotationItem->id = generateUuid();
    }

    /**
     * Handle the QuotationItem "created" event.
     *
     * @param QuotationItem $quotationItem
     * @return void
     */
    public function created(QuotationItem $quotationItem): void
    {
        //
    }

    /**
     * Handle the QuotationItem "updated" event.
     *
     * @param QuotationItem $quotationItem
     * @return void
     */
    public function updated(QuotationItem $quotationItem): void
    {
        //
    }

    /**
     * Handle the QuotationItem "deleted" event.
     *
     * @param QuotationItem $quotationItem
     * @return void
     */
    public function deleted(QuotationItem $quotationItem): void
    {
        //
    }

    /**
     * Handle the QuotationItem "restored" event.
     *
     * @param QuotationItem $quotationItem
     * @return void
     */
    public function restored(QuotationItem $quotationItem): void
    {
        //
    }

    /**
     * Handle the QuotationItem "force deleted" event.
     *
     * @param QuotationItem $quotationItem
     * @return void
     */
    public function forceDeleted(QuotationItem $quotationItem): void
    {
        //
    }
}
