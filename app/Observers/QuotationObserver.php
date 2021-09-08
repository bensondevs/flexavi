<?php

namespace App\Observers;

use App\Models\Quotation;

class QuotationObserver
{
    /**
     * Handle the Quotation "created" event.
     *
     * @param  \App\Models\Quotation  $quotation
     * @return void
     */
    public function created(Quotation $quotation)
    {
        $quotation->calculateTotal();
    }

    /**
     * Handle the Quotation "updated" event.
     *
     * @param  \App\Models\Quotation  $quotation
     * @return void
     */
    public function updated(Quotation $quotation)
    {
        if ($quotation->isDirty('amount') ||
            $quotation->isDirty('vat_percentage') ||
            $quotation->isDirty('discount_amount')) {
            $quotation->calculateTotal();
        }
    }

    /**
     * Handle the Quotation "deleted" event.
     *
     * @param  \App\Models\Quotation  $quotation
     * @return void
     */
    public function deleted(Quotation $quotation)
    {
        //
    }

    /**
     * Handle the Quotation "restored" event.
     *
     * @param  \App\Models\Quotation  $quotation
     * @return void
     */
    public function restored(Quotation $quotation)
    {
        //
    }

    /**
     * Handle the Quotation "force deleted" event.
     *
     * @param  \App\Models\Quotation  $quotation
     * @return void
     */
    public function forceDeleted(Quotation $quotation)
    {
        //
    }
}
