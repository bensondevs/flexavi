<?php

namespace App\Policies\Company\Invoice;

use App\Enums\Invoice\InvoiceStatus as Status;
use App\Models\{Customer\Customer, Invoice\Invoice, User\User};
use App\Services\Invoice\InvoiceService;
use Illuminate\Auth\Access\HandlesAuthorization;

class InvoicePolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any invoices.
     *
     * @param User $user
     * @return bool
     */
    public function viewAny(User $user): bool
    {
        return $user->hasDirectPermissionTwo('view any invoices');
    }

    /**
     * Determine whether the user can view invoice.
     *
     * @param User $user
     * @param Invoice $invoice
     * @return bool
     */
    public function view(User $user, Invoice $invoice): bool
    {
        return $user->hasCompanyDirectPermission($invoice->company_id, 'view invoices');
    }

    /**
     * Determine whether the user can view any customer invoices.
     *
     * @param User $user
     * @param Customer $customer
     * @return bool
     */
    public function viewAnyCustomer(User $user, Customer $customer): bool
    {
        return $user->hasCompanyDirectPermission($customer->company_id, 'view invoices');
    }

    /**
     * Determine whether the user can create invoice.
     *
     * @param User $user
     * @return bool
     */
    public function create(User $user): bool
    {
        return $user->hasDirectPermissionTwo('create invoices');
    }

    /**
     * Determine whether the user can send invoice.
     *
     * @param User $user
     * @param Invoice $invoice
     * @return bool
     */
    public function send(User $user, Invoice $invoice): bool
    {
        return $user->hasCompanyDirectPermission($invoice->company_id, 'send invoices');
    }

    /**
     * Determine whether the user can print invoice.
     *
     * @param User $user
     * @param Invoice $invoice
     * @return bool
     */
    public function print(User $user, Invoice $invoice): bool
    {
        return $user->hasCompanyDirectPermission($invoice->company_id, 'print invoices');
    }

    /**
     * Determine whether the user can send first invoice reminder
     *
     * @param User $user
     * @param Invoice $invoice
     * @return bool
     */
    public function sendFirstReminder(User $user, Invoice $invoice): bool
    {
        if ($invoice->status >= Status::FirstReminderSent) {
            return abort(403, 'First reminder has already been sent!');
        }

        return $this->sendReminder($user, $invoice);
    }

    /**
     * Determine whether the user can send invoice reminder.
     *
     * @param User $user
     * @param Invoice $invoice
     * @return bool
     */
    public function sendReminder(User $user, Invoice $invoice): bool
    {
        if ($invoice->status < Status::PaymentOverdue) {
            return abort(403, 'Cannot remind non-overdue invoice.');
        }

        if ($invoice->status >= Status::ThirdReminderSent) {
            return abort(403, 'Last reminder has been sent, if needed please send debt collector to the customer');
        }

        return $user->hasCompanyDirectPermission($invoice->company_id, 'send reminder invoices');
    }

    /**
     * Determine whether the user can send second invoice reminder
     *
     * @param User $user
     * @param Invoice $invoice
     * @return bool
     */
    public function sendSecondReminder(User $user, Invoice $invoice): bool
    {
        if ($invoice->status >= Status::SecondReminderSent) {
            return abort(403, 'Second reminder has already been sent!');
        }

        return $this->sendReminder($user, $invoice);
    }

    /**
     * Determine whether the user can send third invoice reminder
     *
     * @param User $user
     * @param Invoice $invoice
     * @return bool
     */
    public function sendThirdReminder(User $user, Invoice $invoice): bool
    {
        if ($invoice->status >= Status::ThirdReminderSent) {
            return abort(403, 'Third reminder has already been sent!');
        }

        return $this->sendReminder($user, $invoice);
    }

    /**
     * Determine whether the user can change invoice status.
     *
     * @param User $user
     * @param Invoice $invoice
     * @param int $selectedStatus
     * @return bool
     */
    public function changeStatus(User $user, Invoice $invoice, int $selectedStatus): bool
    {
        if ($invoice->status === $selectedStatus) {
            return abort(403, 'Invoice already has this status!');
        }

        if (!in_array($selectedStatus, InvoiceService::AVAILABLE_INVOICE_ACTIONS[$invoice->status])) {
            return abort(403, 'Cannot change invoice status to this status!');
        }

        return $user->hasCompanyDirectPermission($invoice->company_id, 'change status invoices');
    }

    /**
     * Determine whether the user can update invoice.
     *
     * @param User $user
     * @param Invoice $invoice
     * @return bool
     */
    public function update(User $user, Invoice $invoice): bool
    {
        if ($invoice->canBeEdited()) {
            return abort(403, 'Cannot delete invoice in this stage.');
        }
        return $user->hasCompanyDirectPermission($invoice->company_id, 'edit invoices');
    }

    /**
     * Determine whether the user can delete invoice.
     *
     * @param User $user
     * @param Invoice $invoice
     * @return bool
     */
    public function delete(User $user, Invoice $invoice): bool
    {
        if (!$invoice->canBeDeleted()) {
            return abort(403, 'Cannot delete invoice in this stage.');
        }

        if ($invoice->isPaid()) {
            return abort(403, 'Cannot delete paid invoice.');
        }

        return $user->hasCompanyDirectPermission($invoice->company_id, 'delete invoices');
    }

    /**
     * Determine whether the user can restore invoice.
     *
     * @param User $user
     * @param Invoice $invoice
     * @return bool
     */
    public function restore(User $user, Invoice $invoice): bool
    {
        return $user->hasCompanyDirectPermission($invoice->company_id, 'restore invoices');
    }


    /**
     * Determine whether the user can force delete invoice.
     *
     * @param User $user
     * @param Invoice $invoice
     * @return bool
     */
    public function forceDelete(User $user, Invoice $invoice): bool
    {
        if (!$invoice->canBeDeleted()) {
            return abort(403, 'Cannot delete invoice in this stage.');
        }

        if ($invoice->isPaid()) {
            return abort(403, 'Cannot delete paid invoice.');
        }

        return $user->hasCompanyDirectPermission($invoice->company_id, 'force delete invoices');
    }

    /**
     * Determine whether the user can draft invoices
     *
     * @param User $user
     * @param Invoice|null $invoice
     * @return bool
     */
    public function draft(User $user, Invoice $invoice = null): bool
    {
        if (!$invoice) {
            return $user->hasDirectPermissionTwo('create invoices');
        }

        if (!$invoice->canBeEdited()) {
            return abort(403, 'Cannot edit invoice in this stage.');
        }

        return $user->hasCompanyDirectPermission($invoice->company_id, 'edit invoices');
    }
}
