<?php

namespace App\Policies;

use Illuminate\Auth\Access\HandlesAuthorization;

use App\Models\User;
use App\Models\Invoice;
use App\Models\InvoiceItem;

use App\Enums\Invoice\InvoiceStatus;

class InvoiceItemPolicy
{
    use HandlesAuthorization;

    public function viewAny(User $user, Invoice $invoice)
    {
        return $user->hasCompanyPermission($invoice->company_id, 'view any invoice items');
    }

    public function view(User $user, InvoiceItem $invoiceItem)
    {
        return $user->hasCompanyPermission($invoiceItem->company_id, 'view invoice items');
    }

    public function create(User $user, Invoice $invoice)
    {
        if ($invoice->status >= InvoiceStatus::Sent) {
            return abort(422, 'This invoice is no longer can be edited.');
        }

        return $user->hasCompanyPermission($invoice->company_id, 'create invoice items');
    }

    public function update(User $user, InvoiceItem $invoiceItem)
    {
        $invoice = $invoiceItem->invoice;
        if ($invoice->status >= InvoiceStatus::Sent) {
            return abort(422, 'This invoice is no longer can be edited.');
        }

        return $user->hasCompanyPermission($invoiceItem->company_id, 'edit invoice items');
    }

    public function delete(User $user, InvoiceItem $invoiceItem)
    {
        $invoice = $invoiceItem->invoice;
        if ($invoice->status >= InvoiceStatus::Sent) {
            return abort(422, 'This invoice is no longer can be edited.');
        }

        return $user->hasCompanyPermission($invoiceItem->company_id, 'delete invoice items');
    }

    public function restore(User $user, InvoiceItem $invoiceItem)
    {
        $invoice = $invoiceItem->invoice;
        if ($invoice->status >= InvoiceStatus::Sent) {
            return abort(422, 'This invoice is no longer can be edited.');
        }
        
        return $user->hasCompanyPermission($invoiceItem->company_id, 'restore invoice items');
    }

    public function forceDelete(User $user, InvoiceItem $invoiceItem)
    {
        $invoice = $invoiceItem->invoice;
        if ($invoice->status >= InvoiceStatus::Sent) {
            return abort(422, 'This invoice is no longer can be edited.');
        }
        
        return $user->hasCompanyPermission($invoiceItem->company_id, 'force delete invoice items');
    }
}
