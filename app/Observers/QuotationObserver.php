<?php

namespace App\Observers;

use App\Enums\Quotation\QuotationStatus;
use App\Models\Quotation\Quotation;
use App\Services\Quotation\QuotationLoggingService;
use App\Services\Quotation\QuotationService;

class QuotationObserver
{
    /**
     * Handle the Quotation "creating" event.
     *
     * @param Quotation $quotation
     * @return void
     */
    public function creating(Quotation $quotation): void
    {
        $quotation->id = generateUuid();
        if (!$quotation->expiry_date) {
            $expiryDate = carbon()->now()->addDays(14);
            $quotation->expiry_date = $expiryDate;
        }
    }

    /**
     * Handle the Quotation "saved" event.
     *
     * @param Quotation $quotation
     * @return void
     */
    public function saved(Quotation $quotation): void
    {
        //
    }

    /**
     * Handle the Quotation "saving" event.
     *
     * @param Quotation $quotation
     * @return void
     */
    public function saving(Quotation $quotation): void
    {
        //
    }

    /**
     * Handle the Quotation "created" event.
     *
     * @param Quotation $quotation
     * @return void
     */
    public function created(Quotation $quotation): void
    {
        if ($user = auth()->user()) {
            QuotationLoggingService::created($quotation->fresh());
        }
    }

    /**
     * Handle the Quotation "updating" event.
     *
     * @param Quotation $quotation
     * @return void
     */
    public function updating(Quotation $quotation): void
    {
        //
    }

    /**
     * Handle the Quotation "updated" event.
     *
     * @param Quotation $quotation
     * @return void
     */
    public function updated(Quotation $quotation): void
    {
        QuotationLoggingService::updated($quotation);
        if ($quotation->getOriginal('status') !== QuotationStatus::Sent && $quotation->status === QuotationStatus::Sent) {
            app(QuotationService::class)->sendQuotationMail($quotation);
        }
    }

    /**
     * Handle the Quotation "restored" event.
     *
     * @param Quotation $quotation
     * @return void
     */
    public function restored(Quotation $quotation): void
    {
        if ($user = auth()->user()) {
            QuotationLoggingService::restored($quotation);
        }
    }

    /**
     * Handle the Quotation "deleted" event.
     *
     * @param Quotation $quotation
     * @return void
     */
    public function deleted(Quotation $quotation): void
    {
        if (!$quotation->isForceDeleting()) {
            QuotationLoggingService::deleted($quotation);
        }
    }

    /**
     * Handle the Quotation "force deleted" event.
     *
     * @param Quotation $quotation
     * @return void
     */
    public function forceDeleted(Quotation $quotation): void
    {
        //
    }
}
