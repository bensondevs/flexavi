<?php

namespace App\Policies;

use Illuminate\Auth\Access\HandlesAuthorization;

use App\Models\User;
use App\Models\Invoice;
use App\Models\PaymentTerm;

class PaymentTermPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any payment terms.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Invoice  $invoice
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function viewAny(User $user, Invoice $invoice)
    {
        return $user->hasCompanyPermission($invoice->company_id, 'view any payment terms');
    }

    /**
     * Determine whether the user can view payment term.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\PaymentTerm  $paymentTerm
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function view(User $user, PaymentTerm $paymentTerm)
    {
        return $user->hasCompanyPermission($paymentTerm->company_id, 'view payment terms');
    }

    /**
     * Determine whether the user can create payment term.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Invoice  $invoice
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function create(User $user, Invoice $invoice)
    {
        return $user->hasCompanyPermission($invoice->company_id, 'create payment terms');
    }

    /**
     * Determine whether the user can update payment term.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Payment Term  $paymentTerm
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function update(User $user, PaymentTerm $paymentTerm)
    {
        return $user->hasCompanyPermission($paymentTerm->company_id, 'edit payment terms');
    }

    /**
     * Determine whether the user can delete payment term.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Payment Term  $paymentTerm
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function delete(User $user, PaymentTerm $paymentTerm)
    {
        return $user->hasCompanyPermission($paymentTerm->company_id, 'delete payment terms');
    }

    /**
     * Determine whether the user can restore payment term.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Payment Term  $paymentTerm
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function restore(User $user, PaymentTerm $paymentTerm)
    {
        return $user->hasCompanyPermission($paymentTerm->company_id, 'restrore payment terms');
    }

    /**
     * Determine whether the user can force delete payment term.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Payment Term  $paymentTerm
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function forceDelete(User $user, PaymentTerm $paymentTerm)
    {
        return $user->hasCompanyPermission($paymentTerm->company_id, 'force delete payment terms');
    }
}
