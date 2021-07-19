<?php

namespace App\Policies;

use Illuminate\Auth\Access\HandlesAuthorization;

use App\Models\Invoice;
use App\Models\User;

class InvoicePolicy
{
    use HandlesAuthorization;

    public function viewAny(User $user)
    {
        return $user->hasPermissionTo('view any invoices');
    }

    public function view(User $user, Invoice $invoice)
    {
        return $user->hasCompanyPermission($invoice->company_id, 'view invoices');
    }

    public function create(User $user)
    {
        return $user->hasPermissionTo('create invoices');
    }

    public function update(User $user, Invoice $invoice)
    {
        return $user->hasCompanyPermission($invoice->company_id, 'edit invoices');
    }

    public function delete(User $user, Invoice $invoice)
    {
        return $user->hasCompanyPermission($invoice->company_id, 'delete invoices');
    }

    public function restore(User $user, Invoice $invoice)
    {
        return $user->hasCompanyPermission($invoice->company_id, 'restrore invoices');
    }

    public function forceDelete(User $user, Invoice $invoice)
    {
        return $user->hasCompanyPermission($invoice->company_id, 'force delete invoices');
    }
}
