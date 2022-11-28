<?php

namespace App\Policies\Company\Account;

use App\Models\Customer\Customer;
use App\Models\Employee\Employee; 
use App\Models\Owner\Owner;
use App\Models\User\User;
use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Database\Eloquent\Model;

class AccountPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the account is deleted or not
     * (it returns true if the account is deleted)
     *
     * @param Model $accountable
     * @param mixed|null $addressable |null
     * @return bool
     */
    public function isAccountDeleted(Model $accountable): bool
    {
        if ($accountable instanceof User) {
            switch (true) {
                case !is_null($accountable->deleted_at):
                    return true;
                case is_null($accountable->role_model):
                    return true;
                case isset($accountable->role_model) && $accountable->role_model->deleted_at:
                    return true;
                default:
                    return false;
            }
        }

        if ($accountable instanceof Owner || $accountable instanceof Employee) {
            return $accountable->deleted_at ? true : false;
        }

        if ($accountable instanceof Customer) {
            return $accountable->deleted_at ? true : false;
        }
    }
}
