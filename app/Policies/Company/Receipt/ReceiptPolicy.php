<?php

namespace App\Policies\Company\Receipt;

use App\Models\Receipt\Receipt;
use App\Models\User\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class ReceiptPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any models.
     *
     * @param  \App\Models\User\User  $user
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function viewAny(User $user)
    {
        return $user->hasDirectPermissionTwo('view any receipts');
    }

    /**
     * Determine whether the user can view the model.
     *
     * @param  \App\Models\User\User  $user
     * @param  \App\Models\Receipt\Receipt  $receipt
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function view(User $user, Receipt $receipt)
    {
        return $user->hasCompanyDirectPermission($receipt->company_id, 'view receipts');
    }

    /**
     * Determine whether the user can create models.
     *
     * @param  \App\Models\User\User  $user
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function create(User $user)
    {
        return $user->hasDirectPermissionTwo('create receipts');
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param  \App\Models\User\User  $user
     * @param  \App\Models\Receipt\Receipt  $receipt
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function update(User $user, Receipt $receipt)
    {
        return $user->hasCompanyDirectPermission($receipt->company_id, 'edit receipts');
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param  \App\Models\User\User  $user
     * @param  \App\Models\Receipt\Receipt  $receipt
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function delete(User $user, Receipt $receipt)
    {
        return $user->hasCompanyDirectPermission($receipt->company_id, 'delete receipts');
    }

    /**
     * Determine whether the user can restore the model.
     *
     * @param  \App\Models\User\User  $user
     * @param  \App\Models\Receipt\Receipt  $receipt
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function restore(User $user, Receipt $receipt)
    {
        return $user->hasCompanyDirectPermission($receipt->company_id, 'restore receipts');
    }

    /**
     * Determine whether the user can permanently delete the model.
     *
     * @param  \App\Models\User\User  $user
     * @param  \App\Models\Receipt\Receipt  $receipt
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function forceDelete(User $user, Receipt $receipt)
    {
        return $user->hasCompanyDirectPermission($receipt->company_id, 'force delete receipts');
    }
}
