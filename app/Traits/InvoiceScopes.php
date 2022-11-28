<?php

namespace App\Traits;

use App\Enums\Invoice\InvoiceStatus;
use Illuminate\Database\Eloquent\Builder;

trait InvoiceScopes
{
    /**
     * Create callable `hasBeenOverdue` invoices
     *
     * @param Builder $builder
     * @return Builder
     */
    public function scopeHasBeenOverdue(Builder $builder): Builder
    {
        return $builder->where('due_date', '<=', carbon()->now())
            ->where('status', InvoiceStatus::Sent);
    }

    /**
     * create callable scope `firstReminderOverdue` invoices
     *
     * @param Builder $builder
     * @return Builder
     */
    public function scopeHasBeenFirstReminderOverdue(Builder $builder): Builder
    {
        return $builder->unpaid()->whereHas('reminder', function ($query) {
            $query->where('second_reminder_at', '<=', carbon()->now());
        })->where('status', InvoiceStatus::FirstReminderSent);
    }

    /**
     * Create callable scope `secondReminderOverdue` invoices
     *
     * @param Builder $builder
     * @return Builder
     */
    public function scopeHasBeenSecondReminderOverdue(Builder $builder): Builder
    {
        return $builder->unpaid()->whereHas('reminder', function ($query) {
            $query->where('third_reminder_at', '<=', carbon()->now());
        })->where('status', InvoiceStatus::SecondReminderSent);
    }

    /**
     * Create callable scope `thirdReminderOverdue` invoices
     *
     * @param Builder $builder
     * @return Builder
     */
    public function scopeHasBeenThirdReminderOverdue(Builder $builder): Builder
    {
        return $builder->unpaid()->whereHas('reminder', function ($query) {
            $query->where('sent_to_debt_collector_at', '<', carbon()->now());
        })->where('status', InvoiceStatus::ThirdReminderSent);
    }

    /**
     * Create callable `unpaid` invoices
     *
     * @param Builder $builder
     * @return Builder
     */
    public function scopeUnpaid(Builder $builder): Builder
    {
        return $builder->whereNotIn('status', [InvoiceStatus::Paid, InvoiceStatus::PaidViaDebtCollector, InvoiceStatus::Drafted]);
    }

    /**
     * Create callable `paid` invoices
     *
     * @param Builder $builder
     * @return Builder
     */
    public function scopePaid(Builder $builder): Builder
    {
        return $builder->whereIn('status', [InvoiceStatus::Paid, InvoiceStatus::PaidViaDebtCollector]);
    }

    /**
     * Get invoices first reminder sendable
     *
     * @param Builder $builder
     * @return Builder
     */
    public function scopeFirstReminderSendable(Builder $builder): Builder
    {
        return $builder->where('auto_reminder_activated', true)
            ->whereHas('reminder', function (Builder $query) {
                $query->where('first_reminder_at', now()->format('Y-m-d'));
            });
    }

    /**
     * Get invoices second reminder sendable
     *
     * @param Builder $builder
     * @return Builder
     */
    public function scopeSecondReminderSendable(Builder $builder): Builder
    {
        return $builder->where('auto_reminder_activated', true)
            ->whereHas('reminder', function (Builder $query) {
                $query->where('second_reminder_at', now()->format('Y-m-d'));
            });
    }

    /**
     * Get invoices third reminder sendable
     *
     * @param Builder $builder
     * @return Builder
     */
    public function scopeThirdReminderSendable(Builder $builder): Builder
    {
        return $builder->where('auto_reminder_activated', true)
            ->whereHas('reminder', function (Builder $query) {
                $query->where('third_reminder_at', now()->format('Y-m-d'));
            });
    }

    /**
     * Get invoice send debt collector sendable
     *
     * @param Builder $builder
     * @return Builder
     */
    public function scopeSendDebtCollectorReminderSendable(Builder $builder): Builder
    {
        return $builder->where('auto_reminder_activated', true)->whereHas('reminder', function (Builder $query) {
            $query->where('sent_to_debt_collector_at', now()->format('Y-m-d'));
        });
    }
}
