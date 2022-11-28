<?php

namespace App\Services\Quotation;

use App\Enums\Quotation\QuotationStatus;
use App\Models\Quotation\Quotation;

class QuotationLoggingService
{
    const EXCLUDED_ATTRIBUTES = [
        'company_id',
        'customer_id',
        'amount',
        'potential_amount',
        'vat_percentage',
        'discount_amount',
        'total_amount',
        'status',
        'nullified_at',
        'updated_at',
    ];

    /**
     * Logging quotation updated
     *
     * @param Quotation $quotation
     * @return void
     */
    public static function updated(Quotation $quotation): void
    {
        if ($user = auth()->user()) {

            $quotation = $quotation->load('customer');
            if (in_array('status', array_keys($quotation->getChanges()))) {
                $oldValue = $quotation->getOriginal();
                $oldValue['status_description'] = QuotationStatus::getDescription($oldValue['status'] ?? QuotationStatus::Drafted);
                $newValue = $quotation->toArray();
                QuotationLogService::make('status_changed', $quotation)->by($user)
                    ->with('old', $oldValue)
                    ->with('new', $newValue)
                    ->write();
            }

            $changes = array_diff_key($quotation->getChanges(), array_flip(self::EXCLUDED_ATTRIBUTES));
            foreach ($changes as $attribute => $value) {
                $oldValue = $quotation->getOriginal();
                $oldValue['status_description'] = QuotationStatus::getDescription($oldValue['status']);
                $newValue = $quotation->toArray();
                QuotationLogService::make('updated', $quotation)->by($user)
                    ->with('old', $oldValue)
                    ->with('new', $newValue)
                    ->with('column', $attribute)
                    ->write();
            }
        }
    }

    /**
     * Logging quotation created
     *
     * @param Quotation $quotation
     * @return void
     */
    public static function created(Quotation $quotation): void
    {
        if ($user = auth()->user()) {
            $quotation = $quotation->fresh()->load('customer');
            QuotationLogService::make('created', $quotation)->by($user)
                ->with('new', $quotation->toArray())
                ->write();
        }
    }

    /**
     * Logging quotation deleted
     *
     * @param Quotation $quotation
     * @return void
     */
    public static function deleted(Quotation $quotation): void
    {
        if ($user = auth()->user()) {
            $quotation = $quotation->load('customer');
            QuotationLogService::make('deleted', $quotation)->by($user)
                ->write();
        }
    }

    /**
     * Logging quotation restored
     *
     * @param Quotation $quotation
     * @return void
     */
    public static function restored(Quotation $quotation): void
    {
        if ($user = auth()->user()) {
            $quotation = $quotation->load('customer');
            QuotationLogService::make('restored', $quotation)->by($user)
                ->write();
        }
    }
}
