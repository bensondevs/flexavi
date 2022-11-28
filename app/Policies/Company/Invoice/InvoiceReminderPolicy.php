<?php

namespace App\Policies\Company\Invoice;

use App\Models\Invoice\Invoice;
use App\Models\User\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class InvoiceReminderPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any invoice reminders.
     *
     * @param User $user
     * @param Invoice $invoice
     * @return bool
     */
    public function viewAny(User $user, Invoice $invoice): bool
    {
        return $user->hasCompanyDirectPermission($invoice->company_id, 'view any invoice reminders');
    }

    /**
     * Determine whether the user can view any invoice reminders.
     *
     * @param User $user
     * @param Invoice $invoice
     * @return bool
     */
    public function update(User $user, Invoice $invoice): bool
    {
        if ($invoice->isPaid()) {
            return abort(403, 'Cannot update invoice reminder for paid invoice');
        }
        return $user->hasCompanyDirectPermission($invoice->company_id, 'edit invoice reminders');
    }
}
