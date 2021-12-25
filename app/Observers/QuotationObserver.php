<?php

namespace App\Observers;

use App\Models\Quotation;

use App\Enums\Quotation\QuotationStatus;

class QuotationObserver
{
    /**
     * Handle the Quotation "creating" event.
     *
     * @param  \App\Models\Quotation  $quotation
     * @return void
     */
    public function creating(Quotation $quotation)
    {
        $quotation->id = generateUuid();
        if (! $quotation->expiry_date) {
            $expiryDate = carbon()->now()->addDays(14);
            $quotation->expiry_date = $expiryDate;
        }
    }

    /**
     * Handle the Quotation "created" event.
     *
     * @param  \App\Models\Quotation  $quotation
     * @return void
     */
    public function created(Quotation $quotation)
    {
        $quotation->calculateTotal();

        if ($user = auth()->user()) {
            activity()
                ->causedBy($user)
                ->performedOn($quotation)
                ->log($user->fullname . ' has created quotation with ID: ' . $quotation->id);
        }
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

        if ($quotation->isDirty('status')) {
            if ($quotation->status == QuotationStatus::Sent) {
                if ($user = auth()->user()) {
                    $message = $user->fullname . ' has sent/print quotation with ID: ';
                    $message .= $quotation->id . '. Now the status of quotation is `Sent`';
                    activity()->causedBy($user)->performedOn($quotation)->log($message);
                }
            }

            if ($quotation->status == QuotationStatus::Revised) {
                if ($user = auth()->user()) {
                    $message = $user->fullname . ' has revised quotation with ID: ';
                    $message .= $quotation->id . '. Now the status of quotation is `Revised`';
                    activity()->causedBy($user)->performedOn($quotation)->log($message);
                }
            }

            if ($quotation->status == QuotationStatus::Honored) {
                if ($user = auth()->user()) {
                    $message = $user->fullname . ' has honred quotation with ID: ';
                    $message .= $quotation->id . '. Now the status of quotation is `Honored`';
                    activity()->causedBy($user)->performedOn($quotation)->log($message);
                }
            }

            if ($quotation->status == QuotationStatus::Cancelled) {
                if ($user = auth()->user()) {
                    $message = $user->fullname . ' has cancelled quotation with ID: ';
                    $message .= $quotation->id . '. Now the status of quotation is `Cancelled`';
                    activity()->causedBy($user)->performedOn($quotation)->log($message);
                }
            }
        }

        if ($user = auth()->user()) {
            activity()
                ->causedBy($user)
                ->performedOn($quotation)
                ->log($user->fullname . ' has updated quotation with ID: ' . $quotation->id);
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
        if ($user = auth()->user()) {
            $message = $user->fullname . ' has deleted quotation with ID: ' . $quotation->id; 
            activity()->causedBy($user)->performedOn($quotation)->log($message);
        }
    }

    /**
     * Handle the Quotation "restored" event.
     *
     * @param  \App\Models\Quotation  $quotation
     * @return void
     */
    public function restored(Quotation $quotation)
    {
        if ($user = auth()->user()) {
            $message = $user->fullname . ' has restored quotation with ID: ' . $quotation->id;
            activity()->causedBy($user)->performedOn($quotation)->log($message);
        }
    }

    /**
     * Handle the Quotation "force deleted" event.
     *
     * @param  \App\Models\Quotation  $quotation
     * @return void
     */
    public function forceDeleted(Quotation $quotation)
    {
        if ($user = auth()->user()) {
            $message = $user->fullname . ' has force deleted quotation with ID: ' . $quotation->id; 
            activity()->causedBy($user)->performedOn($quotation)->log($message);
        }
    }
}
