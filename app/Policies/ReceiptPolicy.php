<?php

namespace App\Policies;

use App\Models\Receipt;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class ReceiptPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any models.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function viewAny(User $user)
    {
        return $user->hasPermissionTo('view any receipts');
    }

    /**
     * Determine whether the user can view the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Receipt  $receipt
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function view(User $user, Receipt $receipt)
    {
        return $user->hasCompanyPermission($receipt->company_id, 'view receipts');
    }

    /**
     * Determine whether the user can create models.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function create(User $user)
    {
        return $user->hasPermissionTo('create receipts');
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Receipt  $receipt
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function update(User $user, Receipt $receipt)
    {
        return $user->hasCompanyPermission($receipt->company_id, 'edit receipts');
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Receipt  $receipt
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function delete(User $user, Receipt $receipt)
    {
        return $user->hasCompanyPermission($receipt->company_id, 'delete receipts');
    }

    /**
     * Determine whether the user can restore the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Receipt  $receipt
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function restore(User $user, Receipt $receipt)
    {
        return $user->hasCompanyPermission($receipt->company_id, 'restore receipts');
    }

    /**
     * Determine whether the user can permanently delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Receipt  $receipt
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function forceDelete(User $user, Receipt $receipt)
    {
        return $user->hasCompanyPermission($receipt->company_id, 'force delete receipts');
    }
}
