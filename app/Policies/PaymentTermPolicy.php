<?php

namespace App\Policies;

use Illuminate\Auth\Access\HandlesAuthorization;

use App\Models\User;
use App\Models\Invoice;
use App\Models\PaymentTerm;

class PaymentTermPolicy
{
    use HandlesAuthorization;

    public function viewAny(User $user, Invoice $invoice)
    {
        return $user->hasCompanyPermission($invoice->company_id, 'view any payment terms');
    }

    public function view(User $user, PaymentTerm $paymentTerm)
    {
        return $user->hasCompanyPermission($paymentTerm->company_id, 'view payment terms');
    }

    public function create(User $user, Invoice $invoice)
    {
        return $user->hasCompanyPermission($invoice->company_id, 'create payment terms');
    }

    public function update(User $user, PaymentTerm $paymentTerm)
    {
        return $user->hasCompanyPermission($paymentTerm->company_id, 'edit payment terms');
    }

    public function delete(User $user, PaymentTerm $paymentTerm)
    {
        return $user->hasCompanyPermission($paymentTerm->company_id, 'delete payment terms');
    }

    public function restore(User $user, PaymentTerm $paymentTerm)
    {
        return $user->hasCompanyPermission($paymentTerm->company_id, 'restrore payment terms');
    }

    public function forceDelete(User $user, PaymentTerm $paymentTerm)
    {
        return $user->hasCompanyPermission($paymentTerm->company_id, 'force delete payment terms');
    }
}
