<?php

namespace App\Policies;

use Illuminate\Auth\Access\HandlesAuthorization;

use App\Models\User;
use App\Models\Invoice;
use App\Models\InvoiceItem;

class InvoiceItemPolicy
{
    use HandlesAuthorization;

    public function viewAny(User $user)
    {
        return $user->hasPermissionTo('view any invoice items');
    }

    public function view(User $user, InvoiceItem $invoiceItem)
    {
        return $user->hasCompanyPermission($invoiceItem->company_id, 'view invoice items');
    }

    public function create(User $user, Invoice $invoice)
    {
        return $user->hasPermissionTo('create invoice items');
    }

    public function update(User $user, InvoiceItem $invoiceItem)
    {
        return $user->hasCompanyPermission($invoiceItem->company_id, 'edit invoice items');
    }

    public function delete(User $user, InvoiceItem $invoiceItem)
    {
        return $user->hasCompanyPermission($invoiceItem->company_id, 'delete invoice items');
    }

    public function restore(User $user, InvoiceItem $invoiceItem)
    {
        return $user->hasCompanyPermission($invoiceItem->company_id, 'restore invoice items');
    }

    public function forceDelete(User $user, InvoiceItem $invoiceItem)
    {
        return $user->hasCompanyPermission($invoiceItem->company_id, 'force delete invoice items');
    }
}
