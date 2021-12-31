<?php

namespace App\Policies;

use Illuminate\Auth\Access\HandlesAuthorization;

use App\Models\{ Invoice, User };
use App\Enums\Invoice\InvoiceStatus as Status;

class InvoicePolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any invoices.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function viewAny(User $user)
    {
        return $user->hasPermissionTo('view any invoices');
    }

    /**
     * Determine whether the user can view invoice.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Invoice  $invoice
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function view(User $user, Invoice $invoice)
    {
        return $user->hasCompanyPermission($invoice->company_id, 'view invoices');
    }

    /**
     * Determine whether the user can create invoice.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function create(User $user)
    {
        return $user->hasPermissionTo('create invoices');
    }

    /**
     * Determine whether the user can send invoice.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Invoice  $invoice
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function send(User $user, Invoice $invoice)
    {
        return $user->hasCompanyPermission($invoice->company_id, 'send invoices');
    }

    /**
     * Determine whether the user can print invoice.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Invoice  $invoice
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function print(User $user, Invoice $invoice)
    {
        return $user->hasCompanyPermission($invoice->company_id, 'print invoices');
    }

    /**
     * Determine whether the user can send invoice reminder.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Invoice  $invoice
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function sendReminder(User $user, Invoice $invoice)
    {
        if ($invoice->status < Status::PaymentOverdue) {
            return abort(403, 'Cannot remind non-overdue invoice.');
        }

        if ($invoice->status >= Status::ThirdReminderSent) {
            return abort(403, 'Last reminder has been sent, if needed please send debt collector to the customer');
        }

        return $user->hasCompanyPermission($invoice->company_id, 'send reminder invoices');
    }

    /**
     * Determine whether the user can send first invoice reminder
     * 
     * @param  \App\Models\User    $user
     * @param  \App\Models\Invoice $invoice
     * @param  \Illuminate\Auth\Access\Response|bool
     */
    public function sendFirstReminder(User $user, Invoice $invoice)
    {
        if ($invoice->status >= Status::FirstReminderSent) {
            return abort(403, 'First reminder has already been sent!');
        }

        return $this->sendReminder($user, $invoice);
    }

    /**
     * Determine whether the user can send second invoice reminder
     * 
     * @param  \App\Models\User    $user
     * @param  \App\Models\Invoice $invoice
     * @param  \Illuminate\Auth\Access\Response|bool
     */
    public function sendSecondReminder(User $user, Invoice $invoice)
    {
        if ($invoice->status >= Status::SecondReminderSent) {
            return abort(403, 'Second reminder has already been sent!');
        }

        return $this->sendReminder($user, $invoice);
    }

    /**
     * Determine whether the user can send third invoice reminder
     * 
     * @param  \App\Models\User    $user
     * @param  \App\Models\Invoice $invoice
     * @param  \Illuminate\Auth\Access\Response|bool
     */
    public function sendThirdReminder(User $user, Invoice $invoice)
    {
        if ($invoice->status >= Status::ThirdReminderSent) {
            return abort(403, 'Third reminder has already been sent!');
        }

        return $this->sendReminder($user, $invoice);
    }

    /**
     * Determine whether the user can change invoice status.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Invoice  $invoice
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function changeStatus(User $user, Invoice $invoice)
    {
        return $user->hasCompanyPermission($invoice->company_id, 'change status invoices');
    }

    /**
     * Determine whether the user can update invoice.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Invoice  $invoice
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function update(User $user, Invoice $invoice)
    {
        if ($invoice->status >= Status::Sent) {
            return abort(403, 'Cannot update invoice that has been sent.');
        }

        return $user->hasCompanyPermission($invoice->company_id, 'edit invoices');
    }

    /**
     * Determine whether the user can delete invoice.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Invoice  $invoice
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function delete(User $user, Invoice $invoice)
    {
        if ($invoice->status >= Status::Sent) {
            return abort(403, 'Cannot delete invoice that has been sent.');
        }

        return $user->hasCompanyPermission($invoice->company_id, 'delete invoices');
    }

    /**
     * Determine whether the user can restore invoice.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Invoice  $invoice
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function restore(User $user, Invoice $invoice)
    {
        return $user->hasCompanyPermission($invoice->company_id, 'restrore invoices');
    }

    /**
     * Determine whether the user can force delete invoice.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Invoice  $invoice
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function forceDelete(User $user, Invoice $invoice)
    {
        return $user->hasCompanyPermission($invoice->company_id, 'force delete invoices');
    }
}
