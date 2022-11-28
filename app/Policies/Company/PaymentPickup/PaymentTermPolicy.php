<?php

namespace App\Policies\Company\PaymentPickup;

use App\Models\Invoice\Invoice;
use App\Models\PaymentPickup\PaymentTerm;
use App\Models\User\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class PaymentTermPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any payment terms.
     *
     * @param  \App\Models\User\User  $user
     * @param  \App\Models\Invoice\Invoice  $invoice
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function viewAny(User $user, Invoice $invoice)
    {
        return $user->hasCompanyDirectPermission($invoice->company_id, 'view any payment terms');
    }

    /**
     * Determine whether the user can view payment term.
     *
     * @param  \App\Models\User\User  $user
     * @param  \App\Models\PaymentPickup\PaymentTerm  $paymentTerm
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function view(User $user, PaymentTerm $paymentTerm)
    {
        return $user->hasCompanyDirectPermission($paymentTerm->company_id, 'view payment terms');
    }

    /**
     * Determine whether the user can create payment term.
     *
     * @param  \App\Models\User\User  $user
     * @param  \App\Models\Invoice\Invoice  $invoice
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function create(User $user, Invoice $invoice)
    {
        return $user->hasCompanyDirectPermission($invoice->company_id, 'create payment terms');
    }

    /**
     * Determine whether the user can update payment term.
     *
     * @param  \App\Models\User\User  $user
     * @param  \App\Models\Payment Term  $paymentTerm
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function update(User $user, PaymentTerm $paymentTerm)
    {
        return $user->hasCompanyDirectPermission($paymentTerm->company_id, 'edit payment terms');
    }

    /**
     * Determine whether the user can delete payment term.
     *
     * @param  \App\Models\User\User  $user
     * @param  \App\Models\Payment Term  $paymentTerm
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function delete(User $user, PaymentTerm $paymentTerm)
    {
        return $user->hasCompanyDirectPermission($paymentTerm->company_id, 'delete payment terms');
    }

    /**
     * Determine whether the user can restore payment term.
     *
     * @param  \App\Models\User\User  $user
     * @param  \App\Models\Payment Term  $paymentTerm
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function restore(User $user, PaymentTerm $paymentTerm)
    {
        return $user->hasCompanyDirectPermission($paymentTerm->company_id, 'restrore payment terms');
    }

    /**
     * Determine whether the user can force delete payment term.
     *
     * @param  \App\Models\User\User  $user
     * @param  \App\Models\Payment Term  $paymentTerm
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function forceDelete(User $user, PaymentTerm $paymentTerm)
    {
        return $user->hasCompanyDirectPermission($paymentTerm->company_id, 'force delete payment terms');
    }
}
